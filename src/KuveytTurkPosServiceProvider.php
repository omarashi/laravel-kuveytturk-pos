<?php

namespace Omarashi\KuveytTurkPos;

use Illuminate\Support\ServiceProvider;

Class KuveytTurkPosServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/kuveytturk.php' => config_path('kuveytturk.php'),
        ]);
    }
    
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/kuveytturk.php', 'kuveytturk'
        );
    }
}