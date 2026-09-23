<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Device;
use App\Models\DeviceState;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DeviceControlService
{
    public function __construct(private MqttService $mqttService) {}

    public function applyAction(Device $device, string $action, string $source, ?User $user = null): bool
    {
        $action = strtolower($action);

        if (! in_array($action, ['on', 'off'], true)) {
            Log::warning('Invalid device action', [
                'device_id' => $device->id,
                'action' => $action,
            ]);

            return false;
        }

        $state = $action === 'on';

        $device->update([
            'current_state' => $state,
            'status' => true,
            'last_seen_at' => now(),
        ]);

        DeviceState::create([
            'device_id' => $device->id,
            'state' => $state,
            'source' => $source,
        ]);

        if ($user !== null) {
            ActivityLog::create([
                'user_id' => $user->id,
                'device_id' => $device->id,
                'action' => $state ? 'turned_on' : 'turned_off',
                'source' => $source,
            ]);
        }

        return $this->mqttService->publishCommand($device->device_uid, $action);
    }
}
