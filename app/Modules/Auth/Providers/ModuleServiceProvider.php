<?php

namespace App\Modules\Auth\Providers;

use App\Modules\Auth\Models\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }

    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
