<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Admin pode tudo
        Gate::before(function (User $user, string $ability) {
            return $user->is_admin ? true : null;
        });
    }
}
