<?php

namespace PaoloBellini\LaravelEr;

use Illuminate\Support\ServiceProvider;

class LaravelErServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/er.php', 'er'
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/er.php' => config_path('er.php'),
            ], 'er-config');
        }

        $this->commands([
            Commands\GenerateErDiagramCommand::class,
        ]);
    }
}
