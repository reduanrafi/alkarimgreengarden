<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartFlowTraceTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $category = Category::factory()->create(['status' => true]);
        $this->product = Product::factory()->create([
            'category_id' => $category->id,
            'status' => true,
            'stock' => 10,
            'price' => 29.99,
            'discount_price' => null,
        ]);
        $this->user = User::factory()->create();
    }

    public function test_add_payload_items_is_a_sequential_list(): void
    {
        $add = $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->post('/cart/add/'.$this->product->id, ['quantity' => 1]);
        $add->assertOk();

        $data = $add->json();
        $this->assertTrue($data['success']);
        $this->assertSame(1, $data['count']);
        $this->assertCount(1, $data['items']);
        // Front-end consumes items as a plain array (Array.isArray(cart.items))
        $this->assertSame($this->product->id, $data['items'][0]['id']);
        $this->assertSame(1, $data['items'][0]['quantity']);
        $this->assertSame(29.99, $data['subtotal']);
        $this->assertArrayHasKey('shipping_charge', $data);
        $this->assertArrayHasKey('discount', $data);
        $this->assertArrayHasKey('tax', $data);
        $this->assertArrayHasKey('grand_total', $data);

        foreach (['id', 'name', 'price', 'quantity', 'slug', 'stock', 'final_price', 'image', 'brand', 'sku'] as $key) {
            $this->assertArrayHasKey($key, $data['items'][0], "item missing '$key'");
        }
    }

    public function test_cart_refresh_endpoint_returns_sequential_list(): void
    {
        $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->post('/cart/add/'.$this->product->id, ['quantity' => 1])->assertOk();

        $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->get('/cart/items')
            ->assertOk()
            ->assertJsonPath('count', 1)
            ->assertJsonPath('subtotal', 29.99)
            ->assertJsonCount(1, 'items')
            ->assertJsonPath('items.0.id', $this->product->id);
    }

    public function test_plus_and_minus_persist_quantity_via_method_spoofed_posts(): void
    {
        // Exact requests the mini-cart +/- buttons send: POST + _method=PATCH + _token
        $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->post('/cart/add/'.$this->product->id, ['quantity' => 1])->assertOk();

        $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->post('/cart/update/'.$this->product->id, ['_method' => 'PATCH', 'quantity' => 3])
            ->assertOk()
            ->assertJsonPath('count', 3)
            ->assertJsonPath('items.0.quantity', 3)
            ->assertJsonPath('subtotal', 89.97);
        $this->assertSame(3, session('cart')[$this->product->id]['quantity']);

        $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->post('/cart/update/'.$this->product->id, ['_method' => 'PATCH', 'quantity' => 2])
            ->assertOk()
            ->assertJsonPath('count', 2)
            ->assertJsonPath('items.0.quantity', 2)
            ->assertJsonPath('subtotal', 59.98);
        $this->assertSame(2, session('cart')[$this->product->id]['quantity']);
    }

    public function test_remove_via_method_reviewed_post_zeroes_totals(): void
    {
        $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->post('/cart/add/'.$this->product->id, ['quantity' => 1])->assertOk();

$this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->post('/cart/remove/'.$this->product->id, ['_method' => 'DELETE'])
            ->assertOk()
            ->assertJsonPath('count', 0)
            ->assertJsonPath('subtotal', 0)
            ->assertJsonPath('items', []);
        $this->assertEmpty(session('cart', []));
    }

    public function test_buy_now_path_is_unchanged(): void
    {
        $this->actingAs($this->user);
        $this->post('/cart/add/'.$this->product->id, ['quantity' => 2]);
        $this->assertSame(2, session('cart')[$this->product->id]['quantity']);

        $checkout = $this->get('/checkout');
        $checkout->assertOk()->assertSee($this->product->name);
    }

    public function test_proceed_to_checkout_guest_redirects_to_login(): void
    {
        $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->post('/cart/add/'.$this->product->id, ['quantity' => 1])->assertOk();
        $this->get('/checkout')->assertRedirect(route('login'));
    }

    public function test_checkout_loads_with_cart_and_place_order_ajax_json(): void
    {
        $this->actingAs($this->user);
        $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->post('/cart/add/'.$this->product->id, ['quantity' => 2])->assertOk();

        $checkout = $this->get('/checkout');
        $checkout->assertOk()->assertSee($this->product->name)->assertSee('Cash on Delivery');

        // Real front-end submits place-order via fetch + X-Requested-With -> JSON redirect
        $orderResp = $this->withHeader('X-Requested-With', 'XMLHttpRequest')->post('/checkout', [
            'customer_name' => 'Trace User',
            'phone' => '01800000000',
            'email' => 'trace@example.com',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'city' => 'Dhaka',
            'postal_code' => '1200',
            'address' => '123 Trace Street',
            'payment_method' => 'cash_on_delivery',
            'shipping_method' => 'standard',
            'terms' => '1',
        ]);

        $order = Order::firstOrFail();
        $orderResp->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('redirect', route('orders.success', $order));

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'price' => 29.99,
            'quantity' => 2,
        ]);
        $this->assertSame(8, $this->product->fresh()->stock);
        $this->assertEmpty(session('cart', []));
    }

    public function test_checkout_place_order_plain_post_redirects(): void
    {
        $this->actingAs($this->user);
        $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->post('/cart/add/'.$this->product->id, ['quantity' => 1])->assertOk();

        $this->flushHeaders();

        $orderResp = $this->post('/checkout', [
            'customer_name' => 'Trace User',
            'phone' => '01800000000',
            'email' => 'trace@example.com',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'address' => '123 Trace Street',
            'payment_method' => 'cash_on_delivery',
            'terms' => '1',
        ]);

        $order = Order::firstOrFail();
        $orderResp->assertRedirect(route('orders.success', $order));
    }
}