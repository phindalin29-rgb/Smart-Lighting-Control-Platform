<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'user_id',
        'device_id',
        'name',
        'action',
        'scheduled_time',
        'repeat_type',
        'is_active',
        'last_run_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_time' => 'datetime',
            'is_active' => 'boolean',
            'last_run_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
