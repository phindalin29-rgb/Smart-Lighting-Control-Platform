<?php

use App\Http\Controllers\Api\MqttController;
use Illuminate\Support\Facades\Route;

Route::post('/mqtt/status', [MqttController::class, 'status']);
Route::post('/mqtt/command', [MqttController::class, 'command']);
