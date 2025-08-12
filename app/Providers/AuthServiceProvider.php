<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Canto;
use App\Policies\CantoPolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\CantoTipo;
use App\Policies\CantoTipoPolicy;
use App\Models\Feedback;
use App\Policies\FeedbackPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Canto::class => CantoPolicy::class,
        CantoTipo::class => CantoTipoPolicy::class,
        Feedback::class => FeedbackPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
        
        // Gate: somente administradores
        Gate::define('admin', function ($user) {
            return method_exists($user, 'isAdmin') ? $user->isAdmin() : (($user->role ?? null) === 'admin');
        });
    }
}
