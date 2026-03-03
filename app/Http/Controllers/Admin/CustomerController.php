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

            ->when($q !== '', function ($query) use ($q) {

                $query->where(function ($x) use ($q) {

                    $x->where('name', 'like', "%{$q}%")
                        ->orWhere('full_name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");

                    // 如果是数字 → 可能是 ID 或 credit
                    if (is_numeric($q)) {
                        $x->orWhere('id', $q)
                            ->orWhere('credit_balance', $q);
                    }

                    // 搜索 active / inactive
                    if (strtolower($q) === 'active') {
                        $x->orWhere('is_active', true);
                    }

                    if (strtolower($q) === 'inactive') {
                        $x->orWhere('is_active', false);
                    }
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

        $orders = $customer->hasMany(\App\Models\Order::class, 'user_id')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.customers.show', compact('customer', 'orders'));
    }

    // ✅ NEW: Edit page
    public function edit(User $customer)
    {
        abort_unless($customer->role === 'user', 404);

        return view('admin.customers.edit', compact('customer'));
    }

    // ✅ NEW: Update customer
    public function update(Request $request, User $customer)
    {
        abort_unless($customer->role === 'user', 404);

        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'full_name'      => ['nullable', 'string', 'max:255'],
            'email'          => ['nullable', 'email', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:50'],
            'ic_number'      => ['nullable', 'string', 'max:50'],
            'credit_balance' => ['nullable', 'numeric', 'min:0'],
        ]);

        // ✅ 可选：如果 email 需要唯一（但允许不改自己的 email）
        // $data = $request->validate([
        //     'name'  => ['required', 'string', 'max:255'],
        //     'email' => ['nullable', 'email', 'max:255', 'unique:users,email,' . $customer->id],
        //     'phone' => ['nullable', 'string', 'max:50'],
        // ]);

        $data['is_active'] = $request->boolean('is_active');

        $customer->update($data);

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'Customer updated.');
    }

    public function toggle(User $customer)
    {
        abort_unless($customer->role === 'user', 404);

        // ✅ 防止 admin 不小心 toggle 自己（虽然 role 不同，但加了更保险）
        abort_if($customer->id === auth()->id(), 403);

        $customer->update([
            'is_active' => !$customer->is_active,
        ]);

        return back()->with('success', 'Customer status updated.');
    }

    public function adjustCredit(Request $request, User $customer)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type'   => ['required', 'in:add,deduct'],
        ]);

        $amount = (float) $data['amount'];

        if ($data['type'] === 'add') {
            $customer->credit_balance += $amount;
        }

        if ($data['type'] === 'deduct') {
            $customer->credit_balance -= $amount;

            // 防止变负数（可选）
            if ($customer->credit_balance < 0) {
                $customer->credit_balance = 0;
            }
        }

        $customer->save();

        return back()->with('success', 'Credit updated.');
    }

    public function clearCredit(User $customer)
    {
        $customer->update(['credit_balance' => 0]);

        return back()->with('success', 'Credit cleared.');
    }
}
