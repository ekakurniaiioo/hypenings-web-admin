<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Article::class => \App\Policies\ArticlePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-users', function ($user) {
            return in_array($user->role, ['superadmin', 'admin']);
        });

        Gate::define('create-articles', function ($user) {
            return in_array($user->role, ['superadmin', 'admin']);
        });

        Gate::define('review-articles', function ($user) {
            return in_array($user->role, ['editor', 'admin', 'superadmin']);
        });

    }
}
