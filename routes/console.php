<?php

use App\Services\AutomationExecutionService;
use App\Services\ScheduleExecutionService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    app(ScheduleExecutionService::class)->runDue();
})->everyMinute()->name('smart-lighting:schedules')->withoutOverlapping();

Schedule::call(function () {
    app(AutomationExecutionService::class)->runDue();
})->everyMinute()->name('smart-lighting:automation')->withoutOverlapping();
