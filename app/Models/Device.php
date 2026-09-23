<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'room_id',
        'name',
        'device_uid',
        'type',
        'ip_address',
        'mac_address',
        'status',
        'current_state',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'current_state' => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function states()
    {
        return $this->hasMany(DeviceState::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function automationRules()
    {
        return $this->hasMany(AutomationRule::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
