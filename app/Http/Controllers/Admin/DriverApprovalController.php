<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DriverApprovalController extends Controller
{
    public function index()
    {
        $drivers = User::query()
            ->where('role', 'driver')
            ->where('driver_status', 'pending_approval')
            ->latest()
            ->paginate(15);

        return view('admin.drivers.index', compact('drivers'));
    }

    public function show(User $driver)
    {
        abort_unless($driver->role === 'driver', 404);

        return view('admin.drivers.show', compact('driver'));
    }

    public function approve(Request $request, User $driver)
    {
        abort_unless($driver->role === 'driver', 404);

        $driver->update([
            'driver_status' => 'approved',
        ]);

        return redirect()
            ->route('admin.drivers.show', $driver)
            ->with('status', 'Driver approved.');
    }

    public function reject(Request $request, User $driver)
    {
        abort_unless($driver->role === 'driver', 404);

        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        // 先不建独立表，简单把原因写进 session flash（你要记录到 DB 我下一步帮你加）
        $driver->update([
            'driver_status' => 'rejected',
        ]);

        return redirect()
            ->route('admin.drivers.show', $driver)
            ->with('status', 'Driver rejected.' . (!empty($data['reason']) ? ' Reason: ' . $data['reason'] : ''));
    }

    public function suspend(Request $request, User $driver)
    {
        abort_unless($driver->role === 'driver', 404);

        $driver->update([
            'driver_status' => 'suspended',
        ]);

        return redirect()
            ->route('admin.drivers.show', $driver)
            ->with('status', 'Driver suspended.');
    }
}
