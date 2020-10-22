<?php

namespace Hbliang\AttributesReplication;

use Illuminate\Support\ServiceProvider;

class ReplicationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/attributesreplication.php', 'attributesreplication');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/attributesreplication.php' => config_path('attributesreplication.php'),
            ], 'config');
        }
    }

    public function register()
    {

    }
}
