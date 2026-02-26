<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $customers = User::query()
            ->where('role', 'user')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($x) use ($q) {
                    $x->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'q'));
    }

    public function show(User $customer)
    {
        abort_unless($customer->role === 'user', 404);

        // 如果你想显示客户订单：
        $orders = $customer->hasMany(\App\Models\Order::class, 'user_id')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.customers.show', compact('customer', 'orders'));
    }
}