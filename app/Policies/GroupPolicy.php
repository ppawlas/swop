<?php

namespace App\Policies;

use App\Group;
use App\User;

class GroupPolicy
{
    public function show(User $user, Group $group)
    {
        return $user->hasRole('manager') && ($user->organization->id === $group->organization->id);
    }
}
