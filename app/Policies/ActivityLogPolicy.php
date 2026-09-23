<?php

namespace App\Policies;

use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ActivityLog $activityLog): bool
    {
        return $activityLog->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ActivityLog $activityLog): bool
    {
        return false;
    }

    public function delete(User $user, ActivityLog $activityLog): bool
    {
        return $activityLog->user_id === $user->id;
    }

    public function restore(User $user, ActivityLog $activityLog): bool
    {
        return false;
    }

    public function forceDelete(User $user, ActivityLog $activityLog): bool
    {
        return false;
    }
}
