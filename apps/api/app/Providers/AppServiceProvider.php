<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contratos\VeiculoRepositorioInterface;
use App\Repositories\Eloquent\VeiculoRepositorio;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(VeiculoRepositorioInterface::class, VeiculoRepositorio::class);
    }

}
