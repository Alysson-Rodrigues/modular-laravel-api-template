<?php

namespace App\Bootstrap\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
    * Modules Providers
    *
    * @var string
    */
    private $providers = 'Modules/*/Providers/ModuleServiceProvider.php';

    /**
     * Modules Namespace
     *
     * @var string
     */
    private $provider = 'App\\Modules\\%s\\Providers\\ModuleServiceProvider';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->getProviders()->each(function ($provider) {
            $this->app->register($provider);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (App::environment('production')) {
            URL::forceScheme('https');
        }
    }

    /**
     * @return Collection
     */
    private function getProviders()
    {
        $files = array_map(function ($file) {
            preg_match("/Modules\/(.*)\/Providers/", $file, $output);
            return sprintf($this->provider, $output[1]);
        }, glob(app_path($this->providers)));

        return new Collection($files);
    }
}