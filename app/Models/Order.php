<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'driver_id',
        'manager_id',
        'service_type',
        'pickup',
        'dropoff',
        'pax',
        'note',
        'schedule_type',
        'scheduled_at',
        'shift',
        'status',
        'assigned_at',
        'payment_type',
        'payment_status',
        'amount',

    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'assigned_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
