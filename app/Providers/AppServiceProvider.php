<?php

namespace App\Providers;

use App\Models\Complaint;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Policies\ComplaintPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends AuthServiceProvider
{
    /**
     * Policy map for models.
     */
    protected $policies = [
        Project::class   => ProjectPolicy::class,
        Task::class      => TaskPolicy::class,
        User::class      => UserPolicy::class,
        Complaint::class => ComplaintPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Superadmin bypasses all gates
        Gate::before(function (User $user, string $ability) {
            if ($user->role === 'superadmin') {
                return true;
            }
        });

        // Gate for viewing any user (admin panel)
        Gate::define('view-any', function (User $user, string $modelClass) {
            if ($modelClass === User::class) {
                return $user->isAdmin();
            }

            return false;
        });
    }
}

