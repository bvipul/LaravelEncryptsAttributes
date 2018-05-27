<?php

namespace Bvipul\EncryptsAttributes;

use Illuminate\Support\ServiceProvider;

class EncryptsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/encryptsAttributes.php' => config_path('encryptsAttributes.php'),
        ], 'config');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/encryptsAttributes.php', 'encAttr');
    }
}
