<?php

namespace App\Providers;

use App\Group;
use App\Report;
use App\Policies\GroupPolicy;
use App\Policies\ReportPolicy;

use App\Organization;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//        Organization::class => OrganizationPolicy::class,
//        User::class => UserPolicy::class,
        Group::class => GroupPolicy::class,
        Report::class => ReportPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        $gate->define('adminOnly', function($user) {
            return $user->hasRole('admin');
        });

        $gate->define('managerOnly', function($user) {
            return $user->hasRole('manager');
        });
    }
}
