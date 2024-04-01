<?php

namespace Tienvx\PactProvider\Tests\TestApplication\Providers;

use Illuminate\Support\ServiceProvider;
use Tienvx\PactProvider\Tests\TestApplication\MessageDispatcher\HasMessageDispatcher;
use Tienvx\PactProvider\Tests\TestApplication\MessageDispatcher\NoMessageDispatcher;
use Tienvx\PactProvider\Tests\TestApplication\StateHandler\HasValuesHandler;
use Tienvx\PactProvider\Tests\TestApplication\StateHandler\NoValuesHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        app()->bind(NoValuesHandler::class);
        app()->bind(HasValuesHandler::class);
        app()->bind(HasMessageDispatcher::class);
        app()->bind(NoMessageDispatcher::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(realpath(__DIR__ . '/../../routes/user.php'));
    }
}
