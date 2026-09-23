<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;

class SchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Schedule $schedule): bool
    {
        return $schedule->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Schedule $schedule): bool
    {
        return $schedule->user_id === $user->id;
    }

    public function delete(User $user, Schedule $schedule): bool
    {
        return $schedule->user_id === $user->id;
    }

    public function restore(User $user, Schedule $schedule): bool
    {
        return false;
    }

    public function forceDelete(User $user, Schedule $schedule): bool
    {
        return false;
    }
}
