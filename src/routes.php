<?php

use Illuminate\Routing\Router;

$routeConfig = [
    'namespace' => 'Tienvx\PactProviderPackage\Controllers',
    'prefix' => '',
    'domain' => null,
    'middleware' => [],
];

app('router')->group($routeConfig, function (Router $router) {
    $config = app('config');

    $router->post($config->get('pactprovider.state_change.url'), [
        'uses' => 'StateChangeController@handle',
        'as' => 'pactprovider.state_change_handle',
    ]);

    $router->get($config->get('pactprovider.messages_url'), [
        'uses' => 'MessageController@dispatch',
        'as' => 'pactprovider.message_dispatch',
    ]);
});
