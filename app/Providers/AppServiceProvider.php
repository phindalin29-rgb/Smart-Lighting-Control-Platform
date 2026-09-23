<?php

namespace App\Providers;

use App\Models\ActivityLog;
use App\Models\AutomationRule;
use App\Models\Device;
use App\Models\Home;
use App\Models\Room;
use App\Models\Schedule;
use App\Policies\ActivityLogPolicy;
use App\Policies\AutomationRulePolicy;
use App\Policies\DevicePolicy;
use App\Policies\HomePolicy;
use App\Policies\RoomPolicy;
use App\Policies\SchedulePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Home::class, HomePolicy::class);
        Gate::policy(Room::class, RoomPolicy::class);
        Gate::policy(Device::class, DevicePolicy::class);
        Gate::policy(Schedule::class, SchedulePolicy::class);
        Gate::policy(AutomationRule::class, AutomationRulePolicy::class);
        Gate::policy(ActivityLog::class, ActivityLogPolicy::class);
    }
}
