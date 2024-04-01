<?php

namespace Tienvx\PactProvider\Tests\TestApplication\Controllers;

use Illuminate\Http\Response;
use Tienvx\PactProvider\Tests\TestApplication\Events\UserCreated;
use Tienvx\PactProvider\Tests\TestApplication\Events\UserDeleted;
use Tienvx\PactProvider\Tests\TestApplication\Events\UserUpdated;

class UserController
{
    public function create(): Response
    {
        event(new UserCreated(123));

        return response('created');
    }

    public function update($id): Response
    {
        event(new UserUpdated($id));

        return response('updated');
    }

    public function delete($id): Response
    {
        event(new UserDeleted($id));

        return response('deleted');
    }
}
