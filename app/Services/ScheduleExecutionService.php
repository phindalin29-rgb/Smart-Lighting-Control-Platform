<?php

namespace App\Services;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ScheduleExecutionService
{
    public function __construct(private DeviceControlService $deviceControlService) {}

    public function runDue(?Carbon $now = null): int
    {
        $now ??= now();
        $currentTime = $now->format('H:i');
        $today = $now->toDateString();
        $weekStart = $now->copy()->startOfWeek()->toDateString();
        $executed = 0;

        $schedules = Schedule::query()
            ->where('is_active', true)
            ->whereTime('scheduled_time', $currentTime)
            ->with(['user', 'device.room.home'])
            ->get();

        foreach ($schedules as $schedule) {
            if (! $this->isDue($schedule, $now, $today, $weekStart)) {
                continue;
            }

            if ($schedule->device->room->home->user_id !== $schedule->user_id) {
                Log::warning('Skipping schedule with mismatched device ownership', [
                    'schedule_id' => $schedule->id,
                ]);

                continue;
            }

            $this->deviceControlService->applyAction(
                $schedule->device,
                $schedule->action,
                'schedule',
                $schedule->user
            );

            $schedule->update(['last_run_at' => $now]);
            $executed++;
        }

        return $executed;
    }

    private function isDue(Schedule $schedule, Carbon $now, string $today, string $weekStart): bool
    {
        if ($schedule->last_run_at === null) {
            return true;
        }

        return match ($schedule->repeat_type) {
            'daily' => $schedule->last_run_at->toDateString() !== $today,
            'weekly' => $schedule->last_run_at->copy()->startOfWeek()->toDateString() !== $weekStart,
            default => false,
        };
    }
}
