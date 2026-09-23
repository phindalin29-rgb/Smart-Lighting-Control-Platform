<?php

namespace App\Policies;

use App\Models\Home;
use App\Models\User;

class HomePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Home $home): bool
    {
        return $home->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Home $home): bool
    {
        return $home->user_id === $user->id;
    }

    public function delete(User $user, Home $home): bool
    {
        return $home->user_id === $user->id;
    }

    public function restore(User $user, Home $home): bool
    {
        return false;
    }

    public function forceDelete(User $user, Home $home): bool
    {
        return false;
    }
}
