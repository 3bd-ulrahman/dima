<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->withCount('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function show(Request $request, Order $order): Response
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        $order->load('items.product.category');

        return Inertia::render('Orders/Show', [
            'order' => $order,
        ]);
    }

    public function checkout(Request $request): Response
    {
        $cart = Cart::where('user_id', $request->user()->id)
            ->with('items.product')
            ->first();

        return Inertia::render('Orders/Checkout', [
            'cart' => $cart,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'delivery_address' => ['required', 'string', 'max:255'],
        ]);

        $cart = Cart::where('user_id', $request->user()->id)
            ->with('items.product')
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $subtotal = $cart->items->sum(fn ($item) => $item->product->price * $item->quantity);
        $deliveryFee = 5.00;
        $total = $subtotal + $deliveryFee;

        $order = Order::create([
            'user_id' => $request->user()->id,
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
            'delivery_address' => $validated['delivery_address'],
            'status' => OrderStatus::Pending,
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'price_at_purchase' => $item->product->price,
                'quantity' => $item->quantity,
            ]);
        }

        $cart->delete();

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order placed successfully!');
    }
}
