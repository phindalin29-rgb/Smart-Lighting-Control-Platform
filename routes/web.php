<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\AiDashboardController;
use App\Http\Controllers\AutomationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/reports', [ReportController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('reports.index');

Route::post('/ai/chat', [AiDashboardController::class, 'chat'])
    ->middleware(['auth', 'verified'])->name('ai.dashboard.chat');

Route::middleware('auth')->group(function () {
    Route::get('devices/status', [DeviceController::class, 'status'])->name('devices.status');
    Route::get('devices/{device}/status', [DeviceController::class, 'deviceStatus'])->name('devices.device-status');
    Route::post('devices/{device}/turn-on', [DeviceController::class, 'turnOn'])->name('devices.turn-on');
    Route::post('devices/{device}/turn-off', [DeviceController::class, 'turnOff'])->name('devices.turn-off');
    Route::post('devices/{device}/control', [DeviceController::class, 'control'])->name('devices.control');
    Route::resource('devices', DeviceController::class);

    Route::resource('homes', HomeController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('automation', AutomationController::class);
    Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('ai', [AiAssistantController::class, 'index'])->name('ai.index');
    Route::post('ai', [AiAssistantController::class, 'chat'])->name('ai.chat');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
