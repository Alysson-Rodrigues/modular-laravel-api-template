<?php

namespace  App\Modules\Comments\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Modules\Comments\Http\Controllers';

    protected $routes = 'app/Modules/Comments/Http/Routes/';

    public function map()
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::middleware(['web', 'auth'])
            ->group(base_path($this->routes . 'web.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware(['api'])
            ->namespace($this->namespace)
            ->group(base_path($this->routes . 'api.php'));
    }
}
