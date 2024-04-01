# Pact Provider Package [![Build Status][actions_badge]][actions_link] [![Coverage Status][coveralls_badge]][coveralls_link] [![Version][version-image]][version-url] [![PHP Version][php-version-image]][php-version-url]

This Laravel Package allow testing Laravel project with [Pact PHP][pact-php].
It support:
* Verify sending messages
* Set up provider state
* Tear down provider state

## Installation

```shell
composer require tienvx/laravel-pact-provider
```

## Documentation

### Register State Handler

```php

namespace App\StateHandler;

use Tienvx\PactProvider\Attribute\AsStateHandler;
use Tienvx\PactProvider\Model\StateValues;
use Tienvx\PactProvider\StateHandler\SetUpInterface;
use Tienvx\PactProvider\StateHandler\TearDownInterface;

#[AsStateHandler(state: 'A user with id dcd79453-7346-4423-ae6e-127c60d8dd20 exists')]
class UserHandler implements SetUpInterface, TearDownInterface
{
    public function setUp(array $params): ?StateValues
    {
        return new StateValues([
            'id' => 123,
        ]);
    }

    public function tearDown(array $params): void
    {
    }
}
```

### Register Message Dispatcher

```php

namespace App\MessageDispatcher;

use Tienvx\PactProvider\Attribute\AsMessageDispatcher;
use Tienvx\PactProvider\Model\Message;
use Tienvx\PactProvider\MessageDispatcher\DispatcherInterface;

#[AsMessageDispatcher(description: 'User created message')]
class UserDispatcher implements DispatcherInterface
{
    public function dispatch(): ?Message
    {
    }
}
```

## License

[MIT](https://github.com/tienvx/laravel-pact-provider/blob/main/LICENSE)

[actions_badge]: https://github.com/tienvx/laravel-pact-provider/workflows/main/badge.svg
[actions_link]: https://github.com/tienvx/laravel-pact-provider/actions

[coveralls_badge]: https://coveralls.io/repos/tienvx/laravel-pact-provider/badge.svg?branch=main&service=github
[coveralls_link]: https://coveralls.io/github/tienvx/laravel-pact-provider?branch=main

[version-url]: https://packagist.org/packages/tienvx/laravel-pact-provider
[version-image]: http://img.shields.io/packagist/v/tienvx/laravel-pact-provider.svg?style=flat

[php-version-url]: https://packagist.org/packages/tienvx/laravel-pact-provider
[php-version-image]: http://img.shields.io/badge/php-8.1.0+-ff69b4.svg

[pact-php]: https://github.com/pact-foundation/pact-php
