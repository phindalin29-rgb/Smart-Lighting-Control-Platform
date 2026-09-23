<?php

namespace App\Policies;

use App\Models\AutomationRule;
use App\Models\User;

class AutomationRulePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, AutomationRule $automationRule): bool
    {
        return $automationRule->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, AutomationRule $automationRule): bool
    {
        return $automationRule->user_id === $user->id;
    }

    public function delete(User $user, AutomationRule $automationRule): bool
    {
        return $automationRule->user_id === $user->id;
    }

    public function restore(User $user, AutomationRule $automationRule): bool
    {
        return false;
    }

    public function forceDelete(User $user, AutomationRule $automationRule): bool
    {
        return false;
    }
}
