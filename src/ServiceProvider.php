<?php

namespace Barryvdh\Debugbar;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Tienvx\PactProviderPackage\Controllers\MessagesController;
use Tienvx\PactProviderPackage\Controllers\StateChangeController;
use Tienvx\PactProviderPackage\Service\StateHandlerManagerInterface;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $configPath = __DIR__ . '/../config/pact_provider.php';
        $this->mergeConfigFrom($configPath, 'pact_provider');

        $this->app->bind(StateHandlerManagerInterface::class, StateHandlerManager::class);
        $this->app->bind(MessageDispatcherManagerInterface::class, MessageDispatcherManager::class);

        $this->app->bind(StateChangeController::class, function (Application $app) {
            return new StateChangeController($app->make(StateHandlerManagerInterface::class));
        });

        $this->app->bind(MessagesController::class, function (Application $app) {
            return new MessagesController(
                $app->make(StateHandlerManagerInterface::class),
                $app->make(MessageDispatcherManagerInterface::class)
            );
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/pact_provider.php';
        $this->publishes([$configPath => $this->getConfigPath()]);

        $this->loadRoutesFrom(realpath(__DIR__ . '/routes.php'));
    }

    /**
     * Get the active router.
     *
     * @return Router
     */
    protected function getRouter()
    {
        return $this->app['router'];
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('pact_provider.php');
    }
}
