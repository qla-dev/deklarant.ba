<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \App\Interfaces\LLMCaller;
use \App\Services\OllamaLLMService;
use \App\Services\OpenrouterLLMService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LLMCaller::class, function ($app) {
            return new OpenrouterLLMService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
