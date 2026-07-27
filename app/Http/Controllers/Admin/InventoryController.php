<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('products.name', 'LIKE', "%{$search}%")
                  ->orWhere('products.sku', 'LIKE', "%{$search}%")
                  ->orWhereHas('category', fn($c) => $c->where('name', 'LIKE', "%{$search}%"));
            });
        }

        if ($filter = $request->get('filter')) {
            if ($filter === 'in') {
                $query->inStock();
            } elseif ($filter === 'low') {
                $query->lowStock();
            } elseif ($filter === 'out') {
                $query->outOfStock();
            }
        }

        if ($sort = $request->get('sort')) {
            $query->matchSort($sort);
        } else {
            $query->latest();
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::active()->get();

        return view('admin.inventory.index', compact('products', 'categories'));
    }

    public function stockInForm()
    {
        $products = Product::select('id', 'name', 'sku', 'stock')->orderBy('name')->get();
        return view('admin.inventory.stock-in', compact('products'));
    }

    public function stockIn(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'date' => ['required', 'date'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        DB::transaction(function () use ($product, $data, &$transaction) {
            $stockBefore = $product->stock;
            $quantity = $data['quantity'];
            $product->increment('stock', $quantity);

            $transaction = InventoryTransaction::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockBefore + $quantity,
                'reference' => 'STOCK-IN-' . now()->format('YmdHis'),
                'supplier' => $data['supplier'] ?? null,
                'purchase_price' => $data['purchase_price'] ?? null,
                'remarks' => $data['remarks'] ?? null,
                'performed_by' => auth()->id(),
                'date' => $data['date'],
            ]);
        });

        logActivity('Stock In', 'Inventory', "{$data['quantity']} units added to '{$product->name}'.");

        $ns = app(\App\Services\NotificationService::class);
        $ns->create('stock_in', 'Inventory', "Stock In: {$product->name}", "{$data['quantity']} units of '{$product->name}' were added to stock.");

        return redirect()->route('admin.inventory.index')
            ->with('success', "Stock added: {$data['quantity']} units of {$product->name}.");
    }

    public function stockOutForm()
    {
        $products = Product::select('id', 'name', 'sku', 'stock')->where('stock', '>', 0)->orderBy('name')->get();
        return view('admin.inventory.stock-out', compact('products'));
    }

    public function stockOut(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'date' => ['required', 'date'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        if ($product->stock < $data['quantity']) {
            return back()->withErrors(['quantity' => "Insufficient stock. Only {$product->stock} units available."])
                ->withInput();
        }

        DB::transaction(function () use ($product, $data, &$transaction) {
            $stockBefore = $product->stock;
            $quantity = $data['quantity'];
            $product->decrement('stock', $quantity);

            $transaction = InventoryTransaction::create([
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => $quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockBefore - $quantity,
                'reference' => 'STOCK-OUT-' . now()->format('YmdHis'),
                'reason' => $data['reason'] ?? null,
                'remarks' => $data['remarks'] ?? null,
                'performed_by' => auth()->id(),
                'date' => $data['date'],
            ]);
        });

        logActivity('Stock Out', 'Inventory', "{$data['quantity']} units removed from '{$product->name}'.");

        $ns = app(\App\Services\NotificationService::class);
        $ns->create('stock_out', 'Inventory', "Stock Out: {$product->name}", "{$data['quantity']} units of '{$product->name}' were removed from stock.");

        return redirect()->route('admin.inventory.index')
            ->with('success', "Stock removed: {$data['quantity']} units of {$product->name}.");
    }

    public function adjustForm()
    {
        $products = Product::select('id', 'name', 'sku', 'stock')->orderBy('name')->get();
        return view('admin.inventory.adjust', compact('products'));
    }

    public function adjust(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'action' => ['required', 'in:increase,decrease,reset'],
            'quantity' => ['required_if:action,increase,decrease', 'nullable', 'integer', 'min:1'],
            'new_stock' => ['required_if:action,reset', 'nullable', 'integer', 'min:0'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'date' => ['required', 'date'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        DB::transaction(function () use ($product, $data, &$transaction) {
            $stockBefore = $product->stock;

            if ($data['action'] === 'increase') {
                $product->increment('stock', $data['quantity']);
            } elseif ($data['action'] === 'decrease') {
                if ($product->stock < $data['quantity']) {
                    throw new \Exception("Insufficient stock.");
                }
                $product->decrement('stock', $data['quantity']);
            } elseif ($data['action'] === 'reset') {
                $product->update(['stock' => $data['new_stock']]);
            }

            $stockAfter = $product->fresh()->stock;
            $change = abs($stockAfter - $stockBefore);

            $transaction = InventoryTransaction::create([
                'product_id' => $product->id,
                'type' => 'adjustment',
                'quantity' => $change,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reference' => 'ADJ-' . now()->format('YmdHis'),
                'reason' => "Adjustment: {$data['action']}",
                'remarks' => $data['remarks'] ?? null,
                'performed_by' => auth()->id(),
                'date' => $data['date'],
            ]);
        });

        logActivity('Adjustment', 'Inventory', "Stock adjusted ({$data['action']}) for '{$product->name}'.");

        return redirect()->route('admin.inventory.index')
            ->with('success', "Stock adjusted for {$product->name}.");
    }

    public function history(Request $request)
    {
        $query = InventoryTransaction::with('product', 'performer')->latest('date');

        if ($search = $request->get('search')) {
            $query->whereHas('product', fn($q) => $q->where('name', 'LIKE', "%{$search}%")->orWhere('sku', 'LIKE', "%{$search}%"));
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($productId = $request->get('product_id')) {
            $query->where('product_id', $productId);
        }

        $transactions = $query->paginate(20)->withQueryString();
        $products = Product::select('id', 'name', 'sku')->orderBy('name')->get();

        return view('admin.inventory.history', compact('transactions', 'products'));
    }

    public function exportCsv(Request $request)
    {
        $query = InventoryTransaction::with('product', 'performer')->latest('date');

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }

        $transactions = $query->get();

        $filename = 'inventory-export-' . now()->format('YmdHis') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Product', 'SKU', 'Type', 'Quantity', 'Stock Before', 'Stock After', 'Reference', 'Reason', 'Supplier', 'Purchase Price', 'Performed By', 'Remarks']);

            foreach ($transactions as $t) {
                fputcsv($handle, [
                    $t->date,
                    $t->product?->name ?? 'Deleted',
                    $t->product?->sku ?? '—',
                    $t->type,
                    $t->quantity,
                    $t->stock_before,
                    $t->stock_after,
                    $t->reference,
                    $t->reason,
                    $t->supplier,
                    $t->purchase_price,
                    $t->performer?->name ?? 'System',
                    $t->remarks,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
