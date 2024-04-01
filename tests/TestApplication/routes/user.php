<?php

use Illuminate\Support\Facades\Route;
use Tienvx\PactProvider\Tests\TestApplication\Controllers\UserController;

Route::post('/create', [UserController::class, 'create']);

Route::put('/update/{id}', [UserController::class, 'update']);

Route::delete('/delete/{id}', [UserController::class, 'delete']);
