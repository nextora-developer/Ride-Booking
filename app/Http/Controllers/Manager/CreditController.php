<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CreditLog;
use Illuminate\Support\Facades\DB;

class CreditController extends Controller
{
    public function index(Request $request)
    {
        $q = User::query()
            ->where('role', 'user')
            ->where('credit_balance', '>', 0);

        if ($request->filled('search')) {
            $s = trim((string) $request->search);
            $q->where(function ($qq) use ($s) {
                $qq->where('full_name', 'like', "%{$s}%")
                    ->orWhere('name', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $customers = $q->orderByDesc('credit_balance')
            ->orderBy('full_name')
            ->paginate(10)
            ->withQueryString();

        $totalCustomers = $customers->total();
        $totalCredit = (clone $q)->sum('credit_balance');

        return view('manager.credits.index', compact('customers', 'totalCustomers', 'totalCredit'));
    }

    public function update(Request $request, User $customer)
    {
        abort_unless($customer->role === 'user', 404);

        $data = $request->validate([
            'credit_balance' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($request, $customer, $data) {

            $before = (float) $customer->credit_balance;
            $after  = (float) $data['credit_balance'];
            $change = $after - $before;

            // 更新余额
            $customer->update(['credit_balance' => $after]);

            // 写 log
            CreditLog::create([
                'customer_id' => $customer->id,
                'manager_id'  => $request->user()->id,
                'before'      => $before,
                'change'      => $change,
                'after'       => $after,
                'action'      => 'update',
                'note'        => $data['note'] ?? null,
            ]);
        });

        return back()->with('status', '挂单金额已更新 ✅');
    }

    public function clear(Request $request, User $customer)
    {
        abort_unless($customer->role === 'user', 404);

        DB::transaction(function () use ($request, $customer) {

            $before = (float) $customer->credit_balance;
            $after  = 0.0;
            $change = $after - $before; // 负数

            $customer->update(['credit_balance' => 0]);

            CreditLog::create([
                'customer_id' => $customer->id,
                'manager_id'  => $request->user()->id,
                'before'      => $before,
                'change'      => $change,
                'after'       => $after,
                'action'      => 'clear',
                'note'        => 'Manager cleared credit',
            ]);
        });

        return back()->with('status', '挂单已清零 ✅');
    }
}
