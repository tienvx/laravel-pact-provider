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
        app()->tag(NoValuesHandler::class, 'pact_provider.state_handler');
        app()->tag(HasValuesHandler::class, 'pact_provider.state_handler');
        app()->tag(HasMessageDispatcher::class, 'pact_provider.message_dispatcher');
        app()->tag(NoMessageDispatcher::class, 'pact_provider.message_dispatcher');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
