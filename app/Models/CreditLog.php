<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditLog extends Model
{
    protected $fillable = [
        'customer_id',
        'manager_id',
        'before',
        'change',
        'after',
        'action',
        'note',
    ];

    protected $casts = [
        'before' => 'decimal:2',
        'change' => 'decimal:2',
        'after'  => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
