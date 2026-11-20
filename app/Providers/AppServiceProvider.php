<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Carbon::setLocale('id');

        foreach ([\App\Models\Post::class, \App\Models\Service::class, \App\Models\Unit::class, \App\Models\Album::class] as $model) {
            $model::observe(\App\Observers\ContentCacheObserver::class);
        }

        // Super Admin memiliki semua akses.
        Gate::before(fn (User $user) => $user->isSuperAdmin() ? true : null);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by(strtolower((string) $request->input('email')).'|'.$request->ip());
        });

        RateLimiter::for('public-form', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });
    }
}
