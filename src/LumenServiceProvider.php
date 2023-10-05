<?php

namespace Barryvdh\Debugbar;

use Laravel\Lumen\Application;

class LumenServiceProvider extends ServiceProvider
{
    /** @var  Application */
    protected $app;

    /**
     * Get the active router.
     *
     * @return Application
     */
    protected function getRouter()
    {
        return $this->app->router;
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return base_path('config/pact_provider.php');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            StateHandlerManagerInterface::class,
            MessageDispatcherManagerInterface::class,
            StateChangeController::class,
            MessagesController::class,
        ];
    }
}
