<?php

namespace Tienvx\PactProvider;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use ReflectionClass;
use Tienvx\PactProvider\Attribute\AsMessageDispatcher;
use Tienvx\PactProvider\Attribute\AsStateHandler;
use Tienvx\PactProvider\Controllers\MessagesController;
use Tienvx\PactProvider\Controllers\StateChangeController;
use Tienvx\PactProvider\Listeners\EventCollector;
use Tienvx\PactProvider\Listeners\EventCollectorInterface;
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
        $this->app->bind(EventCollectorInterface::class, EventCollector::class);

        $this->app->singleton(EventCollector::class, function ($app) {
            return new EventCollector();
        });

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

        $this->app->booted(function (Container $app) {
            $this->autoTagServices($app);
        });
    }

    public function boot(): void
    {
        $configPath = __DIR__ . '/../config/pact_provider.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');

        $this->loadRoutesFrom(realpath(__DIR__ . '/../routes/routes.php'));

        Event::listen('*', function (string $eventName, array $data) {
            $collector = app(EventCollectorInterface::class);
            $collector->collect($eventName, $data);
        });
    }

    protected function getConfigPath(): string
    {
        return config_path('pact_provider.php');
    }

    private function autoTagServices(Container $app): void
    {
        $services = $app->getBindings();

        foreach ($services as $abstract => $closure) {
            $concrete = $this->getConcrete($app, $abstract);
            if (!class_exists($concrete)) {
                continue;
            }

            if ($this->hasAttribute($concrete, AsMessageDispatcher::class)) {
                app()->tag($abstract, 'pact_provider.message_dispatcher');
            }

            if ($this->hasAttribute($concrete, AsStateHandler::class)) {
                app()->tag($abstract, 'pact_provider.state_handler');
            }
        }
    }

    private function getConcrete(Container $app, string $abstract): string
    {
        return $app->getAlias($abstract) ?: $abstract;
    }

    protected function hasAttribute(string $className, string $attribute): bool
    {
        $reflection = new ReflectionClass($className);

        return count($reflection->getAttributes($attribute)) > 0;
    }
}
