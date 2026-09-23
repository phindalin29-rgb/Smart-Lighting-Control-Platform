<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceState extends Model
{
    protected $fillable = [
        'device_id',
        'state',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'state' => 'boolean',
        ];
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
