<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $category = Category::factory()->create(['status' => true]);
        $this->product = Product::factory()->create([
            'category_id' => $category->id,
            'status' => true,
            'stock' => 10,
            'price' => 29.99,
        ]);
    }

    public function test_checkout_page_requires_auth(): void
    {
        $response = $this->get('/checkout');
        $response->assertRedirect('/login');
    }

    public function test_checkout_page_with_empty_cart_redirects(): void
    {
        $response = $this->actingAs($this->user)->get('/checkout');
        $response->assertRedirect('/cart');
        $response->assertSessionHas('error');
    }

    public function test_checkout_only_offers_cash_on_delivery(): void
    {
        $this->actingAs($this->user);
        $this->post('/cart/add/'.$this->product->id, ['quantity' => 1]);

        $response = $this->get('/checkout');

        $response->assertOk()
            ->assertSee('Cash on Delivery')
            ->assertSee('SSLCommerz')
            ->assertSee('Stripe')
            ->assertSee('PayPal')
            ->assertSee('Will be available soon.', false)
            ->assertSee('disabled', false);
    }

    public function test_checkout_store_creates_order(): void
    {
        $this->actingAs($this->user);
        $this->post('/cart/add/'.$this->product->id, ['quantity' => 2]);

        $response = $this->post('/checkout', [
            'customer_name' => 'John Doe',
            'phone' => '0123456789',
            'email' => 'john@example.com',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'address' => '123 Street',
            'payment_method' => 'cash_on_delivery',
            'terms' => '1',
        ]);

        $order = Order::firstOrFail();

        $response->assertRedirect(route('orders.success', $order));
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'John Doe',
            'grand_total' => 72.97,
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'price' => 29.99,
            'quantity' => 2,
        ]);
        $this->assertSame(8, $this->product->fresh()->stock);
        $this->assertEmpty(session('cart', []));
    }

    public function test_checkout_rejects_payment_methods_other_than_cash_on_delivery(): void
    {
        $this->actingAs($this->user);
        $this->post('/cart/add/'.$this->product->id, ['quantity' => 1]);

        $response = $this->from('/checkout')->post('/checkout', [
            'customer_name' => 'John Doe',
            'phone' => '0123456789',
            'email' => 'john@example.com',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'address' => '123 Street',
            'payment_method' => 'sslcommerz',
            'terms' => '1',
        ]);

        $response->assertRedirect('/checkout')
            ->assertSessionHasErrors('payment_method');
        $this->assertDatabaseCount('orders', 0);
    }
}
