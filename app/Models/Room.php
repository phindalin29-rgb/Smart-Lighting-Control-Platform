<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'home_id',
        'name',
        'description',
    ];

    public function home()
    {
        return $this->belongsTo(Home::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
