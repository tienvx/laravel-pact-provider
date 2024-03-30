<?php

use Illuminate\Routing\Router;

$routeConfig = [
    'namespace' => 'Tienvx\PactProvider\Controllers',
    'prefix' => '',
    'domain' => null,
    'middleware' => [],
];

app('router')->group($routeConfig, function (Router $router) {
    $router->post(config('pact_provider.state_change.url'), [
        'uses' => 'StateChangeController@handle',
        'as' => 'pact_provider.state_change_handle',
    ]);

    $router->post(config('pact_provider.messages_url'), [
        'uses' => 'MessagesController@handle',
        'as' => 'pact_provider.message_dispatch',
    ]);
});
