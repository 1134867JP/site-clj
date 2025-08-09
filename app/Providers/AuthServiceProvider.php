<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Canto;
use App\Policies\CantoPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Canto::class => CantoPolicy::class,
    ];

    public function boot(): void
    {
        // nada a fazer aqui
    }
}
