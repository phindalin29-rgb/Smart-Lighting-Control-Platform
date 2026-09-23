<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Str;

class AiService
{
    public function __construct(private DeviceControlService $deviceControlService) {}

    public function handle(User $user, string $message): array
    {
        $message = trim($message);
        $normalized = mb_strtolower($message);

        if ($this->containsAny($normalized, ['តើ', 'status', 'state', 'which light', 'what is on', 'ភ្លើងណាខ្លះ'])) {
            return $this->statusResponse($user);
        }

        if ($this->containsAny($normalized, ['រៀងរាល់ថ្ងៃ', 'daily', 'every day', 'ម៉ោង', ' at '])) {
            return $this->createSchedule($user, $normalized, $message);
        }

        $action = $this->parseAction($normalized);

        if ($action === null) {
            return [
                'intent' => 'unsupported',
                'reply' => 'I could not detect an on or off command. Try: turn on the bedroom light.',
            ];
        }

        if ($this->containsAny($normalized, ['ទាំងអស់', 'all lights', 'every light', 'all devices'])) {
            return $this->controlAll($user, $action);
        }

        return $this->controlNamedDevice($user, $action, $message);
    }

    private function parseAction(string $normalized): ?string
    {
        if ($this->containsAny($normalized, ['បើក', 'turn on', 'switch on', ' power on'])) {
            return 'on';
        }

        if ($this->containsAny($normalized, ['បិទ', 'turn off', 'switch off', ' power off'])) {
            return 'off';
        }

        return null;
    }

    private function controlNamedDevice(User $user, string $action, string $message): array
    {
        $device = $this->ownedDevices($user)
            ->filter(fn (Device $candidate) => $this->deviceNameMatches($candidate->name, $message))
            ->first();

        if ($device === null) {
            return [
                'intent' => 'control_device',
                'action' => $action,
                'reply' => 'I could not find that device in your homes. Check the device name and try again.',
            ];
        }

        $this->deviceControlService->applyAction($device, $action, 'ai', $user);

        return [
            'intent' => 'control_device',
            'device' => $device->name,
            'action' => $action,
            'reply' => "{$device->name} turned ".($action === 'on' ? 'ON' : 'OFF').'.',
        ];
    }

    private function controlAll(User $user, string $action): array
    {
        $devices = $this->ownedDevices($user);

        if ($devices->isEmpty()) {
            return [
                'intent' => 'control_all',
                'reply' => 'You do not have any devices yet.',
            ];
        }

        foreach ($devices as $device) {
            $this->deviceControlService->applyAction($device, $action, 'ai', $user);
        }

        return [
            'intent' => 'control_all',
            'action' => $action,
            'count' => $devices->count(),
            'reply' => "{$devices->count()} devices turned ".($action === 'on' ? 'ON' : 'OFF').'.',
        ];
    }

    private function statusResponse(User $user): array
    {
        $devices = $this->ownedDevices($user);
        $onDevices = $devices->where('current_state', true);

        if ($onDevices->isEmpty()) {
            return [
                'intent' => 'status',
                'reply' => 'No lights are currently ON.',
            ];
        }

        return [
            'intent' => 'status',
            'devices' => $onDevices->pluck('name')->all(),
            'reply' => 'Currently ON: '.$onDevices->pluck('name')->join(', ', ' and ').'.',
        ];
    }

    private function createSchedule(User $user, string $normalized, string $message): array
    {
        $action = $this->parseAction($normalized);
        $time = $this->parseTime($normalized, $message);

        if ($action === null || $time === null) {
            return [
                'intent' => 'create_schedule',
                'reply' => 'I need an action and a time. Example: every day at 18:00 turn on the living room light.',
            ];
        }

        $device = $this->ownedDevices($user)
            ->filter(fn (Device $candidate) => $this->deviceNameMatches($candidate->name, $message))
            ->first();

        if ($device === null) {
            return [
                'intent' => 'create_schedule',
                'reply' => 'I could not find that device in your homes.',
            ];
        }

        Schedule::create([
            'user_id' => $user->id,
            'device_id' => $device->id,
            'name' => Str::title("{$device->name} {$action} {$time}"),
            'action' => $action,
            'scheduled_time' => $time,
            'repeat_type' => 'daily',
            'is_active' => true,
        ]);

        return [
            'intent' => 'create_schedule',
            'device' => $device->name,
            'action' => $action,
            'time' => $time,
            'repeat' => 'daily',
            'reply' => "Schedule created for {$device->name} at {$time} every day.",
        ];
    }

    private function parseTime(string $normalized, string $message): ?string
    {
        if (preg_match('/\b([01]?\d|2[0-3])[:.](\d{2})\b/', $normalized, $matches)) {
            return sprintf('%02d:%02d', (int) $matches[1], (int) $matches[2]);
        }

        if (preg_match('/\b([01]?\d|2[0-3])\s*(ល្ងាច|យប់|ព្រឹក|ថ្ងៃត្រង់)/u', $message, $matches)) {
            $hour = (int) $matches[1];
            $period = $matches[2];

            if ($period === 'ល្ងាច' || $period === 'យប់') {
                $hour = $hour < 12 ? $hour + 12 : $hour;
            }

            if ($period === 'ព្រឹក' && $hour === 12) {
                $hour = 0;
            }

            return sprintf('%02d:00', $hour);
        }

        return null;
    }

    private function ownedDevices(User $user)
    {
        return $user->homes()
            ->with('rooms.devices')
            ->get()
            ->flatMap->rooms
            ->flatMap->devices;
    }

    private function deviceNameMatches(string $deviceName, string $message): bool
    {
        return stripos(mb_strtolower($message), mb_strtolower($deviceName)) !== false
            || stripos(mb_strtolower($deviceName), mb_strtolower($message)) !== false;
    }

    private function containsAny(string $normalized, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($normalized, $needle)) {
                return true;
            }
        }

        return false;
    }
}
