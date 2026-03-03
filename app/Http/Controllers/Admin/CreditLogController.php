<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditLog;
use Illuminate\Http\Request;


class CreditLogController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        $type = $request->type;      // ✅ add/deduct/clear
        $action = $request->action;  // ✅ order_completed/update/clear (可选)

        $logs = CreditLog::with(['customer', 'manager'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($q2) use ($q) {
                    $q2->whereHas(
                        'customer',
                        fn($c) =>
                        $c->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                            ->orWhere('phone', 'like', "%{$q}%")
                            ->orWhere('id', $q)
                    )
                        ->orWhereHas(
                            'manager',
                            fn($m) =>
                            $m->where('name', 'like', "%{$q}%")
                        )
                        ->orWhere('note', 'like', "%{$q}%");
                });
            })

            // ✅ Type filter（重点）
            ->when($type === 'add', fn($query) => $query->where('change', '>', 0))
            ->when($type === 'deduct', fn($query) => $query->where('change', '<', 0))
            ->when($type === 'clear', fn($query) => $query->where('after', '=', 0)->where('action', 'clear'))

            // ✅ Source filter（可选）
            ->when($action, fn($query) => $query->where('action', $action))

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.credit-logs.index', compact('logs', 'q', 'type', 'action'));
    }
}
