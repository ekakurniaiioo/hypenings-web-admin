<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        View::composer('components.topnav', function ($view) {
            $view->with([
                'notifications' => Notification::latest()->take(8)->get(),
                'hasUnread' => Notification::where('is_read', false)->exists(),
            ]);
        });
    }

}
