# Pact Provider Package [![Build Status][actions_badge]][actions_link] [![Coverage Status][coveralls_badge]][coveralls_link] [![Version][version-image]][version-url] [![PHP Version][php-version-image]][php-version-url]

This Laravel Package allow testing Laravel project with [Pact PHP][pact-php].
It support:
* Verify sending messages
* Set up provider state
* Tear down provider state

## Installation

```shell
composer require tienvx/pact-provider-package
```

## Documentation

### Register State Handler

```php

namespace App\StateHandler;

use Tienvx\PactProviderPackage\StateHandler\HandlerInterface;
use Tienvx\PactProviderPackage\StateHandler\SetUpInterface;
use Tienvx\PactProviderPackage\StateHandler\TearDownInterface;

class UserHandler implements HandlerInterface, SetUpInterface, TearDownInterface
{
    public function support(string $state): bool
    {
        return $state === 'A user with id dcd79453-7346-4423-ae6e-127c60d8dd20 exists';
    }

    public function setUp(array $params): void
    {
    }

    public function tearDown(array $params): void
    {
    }
}
```

```php
use App\StateHandler\UserHandler;

app()->tag(UserHandler::class, 'pact_provider.state_handler');
```

### Register Message Dispatcher

```php

namespace App\MessageDispatcher;

use Tienvx\PactProviderPackage\Model\Message;
use Tienvx\PactProviderPackage\MessageDispatcher\DispatcherInterface;

class UserDispatcher implements DispatcherInterface
{
    public function support(string $description): bool
    {
        return $description === 'User created message';
    }

    public function dispatch(): Message
    {
    }
}
```

```php
use App\MessageDispatcher\UserDispatcher;

app()->tag(UserDispatcher::class, 'pact_provider.message_dispatcher');
```

## License

[MIT](https://github.com/tienvx/pact-provider-package/blob/main/LICENSE)

[actions_badge]: https://github.com/tienvx/pact-provider-package/workflows/main/badge.svg
[actions_link]: https://github.com/tienvx/pact-provider-package/actions

[coveralls_badge]: https://coveralls.io/repos/tienvx/pact-provider-package/badge.svg?branch=main&service=github
[coveralls_link]: https://coveralls.io/github/tienvx/pact-provider-package?branch=main

[version-url]: https://packagist.org/packages/tienvx/pact-provider-package
[version-image]: http://img.shields.io/packagist/v/tienvx/pact-provider-package.svg?style=flat

[php-version-url]: https://packagist.org/packages/tienvx/pact-provider-package
[php-version-image]: http://img.shields.io/badge/php-8.0.0+-ff69b4.svg

[pact-php]: https://github.com/pact-foundation/pact-php
