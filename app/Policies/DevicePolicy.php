<?php

namespace App\Policies;

use App\Models\Device;
use App\Models\User;

class DevicePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Device $device): bool
    {
        return $device->room->home->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Device $device): bool
    {
        return $device->room->home->user_id === $user->id;
    }

    public function delete(User $user, Device $device): bool
    {
        return $device->room->home->user_id === $user->id;
    }

    public function restore(User $user, Device $device): bool
    {
        return false;
    }

    public function forceDelete(User $user, Device $device): bool
    {
        return false;
    }
}
