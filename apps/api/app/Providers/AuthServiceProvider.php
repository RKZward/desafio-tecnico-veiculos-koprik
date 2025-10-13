<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Veiculo;
use App\Policies\VeiculoPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Veiculo::class => VeiculoPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Admin pode tudo: se is_admin = true, autoriza sem consultar a policy
        Gate::before(function (User $user, string $ability) {
            return $user->is_admin ? true : null;
        });
    }
}
