<?php

namespace Tienvx\PactProvider\Tests\Feature;

use Orchestra\Testbench\TestCase as Orchestra;
use Tienvx\PactProvider\ServiceProvider;
use Tienvx\PactProvider\Tests\TestApplication\Providers\AppServiceProvider;

class PactProviderTestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
            AppServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.debug', true);
    }
}
