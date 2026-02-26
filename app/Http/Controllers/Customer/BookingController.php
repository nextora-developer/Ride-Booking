<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create()
    {
        return view('customer.book');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_type'  => ['required', 'in:pickup_dropoff,charter,designated_driver,purchase,big_car,driver_only'],
            'pickup'        => ['required', 'string', 'max:255'],
            'dropoff'       => ['required', 'string', 'max:255'],
            'pax'           => ['required', 'integer', 'min:1', 'max:12'],
            'note'          => ['nullable', 'string', 'max:2000'],
            'schedule_type' => ['nullable', 'in:now,scheduled'],
            'scheduled_at'  => ['nullable', 'date', 'required_if:schedule_type,scheduled'],
        ]);

        $scheduleType = $data['schedule_type'] ?? 'now';

        $when = now();
        if ($scheduleType === 'scheduled' && !empty($data['scheduled_at'])) {
            $when = Carbon::parse($data['scheduled_at']);
        }

        $shift = $this->decideShift($when);

        Order::create([
            'user_id'       => $request->user()->id,
            'service_type'  => $data['service_type'],
            'pickup'        => $data['pickup'],
            'dropoff'       => $data['dropoff'],
            'pax'           => (int) $data['pax'],
            'note'          => $data['note'] ?? null,
            'schedule_type' => $scheduleType,
            'scheduled_at'  => ($scheduleType === 'scheduled') ? ($data['scheduled_at'] ?? null) : null,
            'shift'         => $shift,
            'status'        => 'pending',
        ]);

        return redirect()
            ->route('customer.home')
            ->with('status', 'Booking submitted successfully. Waiting for dispatch.');
    }

    private function decideShift(Carbon $dt): string
    {
        // 06:00–17:59 => day, else night
        $hour = (int) $dt->format('H');
        return ($hour >= 6 && $hour <= 17) ? 'day' : 'night';
    }
}
