<?php

namespace Cronpulse\LaravelMonitor;

use Illuminate\Support\ServiceProvider;

class MonitorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Monitor::class, function ($app) {
            return new Monitor(config('monitor.job_key'));
        });

        $this->mergeConfigFrom(__DIR__.'/../config/monitor.php', 'monitor');

        require_once __DIR__ . '/helpers.php';
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/monitor.php' => config_path('monitor.php'),
        ]);
    }
}
