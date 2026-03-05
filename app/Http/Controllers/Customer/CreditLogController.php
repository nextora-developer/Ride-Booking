<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CreditLog;
use Illuminate\Http\Request;

class CreditLogController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $logs = CreditLog::query()
            ->where('customer_id', $userId) // 如果你是 user_id 就改成 user_id
            ->latest()
            ->paginate(5);

        return view('customer.credit-logs.index', compact('logs'));
    }

    public function show(CreditLog $log)
    {
        // ✅ 防止偷看别人
        abort_unless($log->customer_id == auth()->id(), 403); // 如果是 user_id 就改

        return view('customer.credit-logs.show', compact('log'));
    }
}
