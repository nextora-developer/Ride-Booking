<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function create(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->status === 'completed', 403);
        abort_if($order->review()->exists(), 403, '这张订单已经评价过了。');

        return view('customer.reviews.create', compact('order'));
    }

    public function store(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->status === 'completed', 403);
        abort_if($order->review()->exists(), 403, '这张订单已经评价过了。');

        $data = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'order_id' => $order->id,
            'user_id'  => auth()->id(),
            'rating'   => $data['rating'],
            'comment'  => $data['comment'] ?? null,
        ]);

        return redirect()
            ->route('customer.orders.show', $order)
            ->with('success', '感谢您的评价！');
    }
}
