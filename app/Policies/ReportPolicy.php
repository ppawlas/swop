<?php

namespace App\Policies;

use App\Report;
use App\User;

class ReportPolicy
{
    public function show(User $user, Report $report)
    {
        return $user->hasRole('manager') && ($user->id === $report->owner->id);
    }
}
