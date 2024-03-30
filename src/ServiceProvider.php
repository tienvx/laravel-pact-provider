<?php

namespace Tienvx\PactProvider;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Tienvx\PactProvider\Controllers\MessagesController;
use Tienvx\PactProvider\Controllers\StateChangeController;
use Tienvx\PactProvider\Service\MessageDispatcherManager;
use Tienvx\PactProvider\Service\MessageDispatcherManagerInterface;
use Tienvx\PactProvider\Service\StateHandlerManager;
use Tienvx\PactProvider\Service\StateHandlerManagerInterface;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $configPath = __DIR__ . '/../config/pact_provider.php';
        $this->mergeConfigFrom($configPath, 'pact_provider');

        $this->app->bind(StateHandlerManagerInterface::class, StateHandlerManager::class);
        $this->app->bind(MessageDispatcherManagerInterface::class, MessageDispatcherManager::class);

        $this->app->bind(StateChangeController::class, function (Application $app) {
            return new StateChangeController(
                $app->make(StateHandlerManagerInterface::class),
                config('pact_provider.state_change.body')
            );
        });

        $this->app->bind(MessagesController::class, function (Application $app) {
            return new MessagesController(
                $app->make(StateHandlerManagerInterface::class),
                $app->make(MessageDispatcherManagerInterface::class)
            );
        });

        $this->app->bind(MessageDispatcherManager::class, function (Application $app) {
            return new MessageDispatcherManager($app->tagged('pact_provider.message_dispatcher'));
        });

        $this->app->bind(StateHandlerManager::class, function (Application $app) {
            return new StateHandlerManager($app->tagged('pact_provider.state_handler'));
        });
    }

    public function boot(): void
    {
        $configPath = __DIR__ . '/../config/pact_provider.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');

        $this->loadRoutesFrom(realpath(__DIR__ . '/../routes/routes.php'));
    }

    protected function getConfigPath(): string
    {
        return config_path('pact_provider.php');
    }
}
