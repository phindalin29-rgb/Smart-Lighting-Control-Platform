<?php

namespace App\Services;

use App\Models\AutomationRule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutomationExecutionService
{
    public function __construct(private DeviceControlService $deviceControlService) {}

    public function runDue(?Carbon $now = null): int
    {
        $now ??= now();
        $executed = 0;
        $currentTime = $now->format('H:i');

        $rules = AutomationRule::query()
            ->where('is_active', true)
            ->with(['user', 'device.room.home'])
            ->get();

        foreach ($rules as $rule) {
            if (! $this->conditionMatches($rule, $currentTime, $now)) {
                continue;
            }

            if ($rule->device->room->home->user_id !== $rule->user_id) {
                Log::warning('Skipping automation with mismatched device ownership', [
                    'automation_rule_id' => $rule->id,
                ]);

                continue;
            }

            $this->deviceControlService->applyAction(
                $rule->device,
                $rule->action,
                'automation',
                $rule->user
            );

            $rule->update(['last_run_at' => $now]);
            $executed++;
        }

        return $executed;
    }

    private function conditionMatches(AutomationRule $rule, string $currentTime, Carbon $now): bool
    {
        if ($rule->last_run_at !== null && $rule->last_run_at->diffInMinutes($now, true) < 1) {
            return false;
        }

        return match ($rule->condition_type) {
            'time' => $rule->condition_value === $currentTime,
            'device_state' => $this->deviceStateMatches($rule),
            default => false,
        };
    }

    private function deviceStateMatches(AutomationRule $rule): bool
    {
        $expected = strtolower((string) $rule->condition_value);

        if (! in_array($expected, ['on', 'off'], true)) {
            return false;
        }

        return $rule->device->current_state === ($expected === 'on');
    }
}
