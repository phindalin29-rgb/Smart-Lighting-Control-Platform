<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Room $room): bool
    {
        return $room->home->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Room $room): bool
    {
        return $room->home->user_id === $user->id;
    }

    public function delete(User $user, Room $room): bool
    {
        return $room->home->user_id === $user->id;
    }

    public function restore(User $user, Room $room): bool
    {
        return false;
    }

    public function forceDelete(User $user, Room $room): bool
    {
        return false;
    }
}
