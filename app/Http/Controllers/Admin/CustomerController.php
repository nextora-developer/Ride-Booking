<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
            ->with('success', '客户资料已成功更新。');
    }

    public function toggle(User $customer)
    {
        abort_unless($customer->role === 'user', 404);

        // ✅ 防止 admin 不小心 toggle 自己（虽然 role 不同，但加了更保险）
        abort_if($customer->id === auth()->id(), 403);

        $customer->update([
            'is_active' => !$customer->is_active,
        ]);

        return back()->with('success', '客户状态已成功更新。');
    }

    public function adjustCredit(Request $request, User $customer)
    {
        abort_unless($customer->role === 'user', 404);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'action' => ['required', 'in:add,deduct'],
        ]);

        $amount = round((float) $data['amount'], 2);

        DB::transaction(function () use ($customer, $data, $amount) {

            $u = User::whereKey($customer->id)->lockForUpdate()->first();

            $before = (float) ($u->credit_balance ?? 0);

            $after = $data['action'] === 'add'
                ? $before + $amount
                : max(0, $before - $amount);

            $change = $data['action'] === 'add'
                ? $amount
                : -$amount;

            $u->update(['credit_balance' => $after]);

            DB::table('credit_logs')->insert([
                'customer_id' => $u->id,
                'manager_id'  => auth()->id(),
                'before'      => $before,
                'change'      => $change,
                'after'       => $after,
                'action'      => 'update', // 你表里有 order_completed / update / clear
                'note'        => 'Admin credit adjustment',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        });

        return back()->with('success', '挂单额度已成功更新。');
    }

    public function clearCredit(User $customer)
    {
        abort_unless($customer->role === 'user', 404);

        DB::transaction(function () use ($customer) {

            $u = User::whereKey($customer->id)
                ->lockForUpdate()
                ->first();

            $before = (float) ($u->credit_balance ?? 0);

            // 如果本来就是 0，不记录也可以（可选）
            if ($before == 0) {
                return;
            }

            $u->update([
                'credit_balance' => 0,
            ]);

            DB::table('credit_logs')->insert([
                'customer_id' => $u->id,
                'manager_id'  => auth()->id(),
                'before'      => $before,
                'change'      => -$before, // 清零 = 扣掉全部
                'after'       => 0,
                'action'      => 'clear',
                'note'        => 'Admin cleared credit',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        });

        return back()->with('success', '挂单额度已清除。');
    }
}
