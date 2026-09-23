<?php

namespace App\Http\Controllers\Api;

use App\Models\Device;
use App\Services\MqttService;
use Illuminate\Http\Request;

class MqttController
{
    public function __construct(private MqttService $mqttService) {}

    public function status(Request $request)
    {
        $token = config('mqtt.api_token');

        if ($token && $request->bearerToken() !== $token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'device_uid' => 'required|string|max:255',
            'state' => 'required|boolean',
            'online' => 'required|boolean',
        ]);

        $device = Device::where('device_uid', $validated['device_uid'])->first();

        if ($device === null) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        $device->update([
            'status' => $validated['online'],
            'current_state' => $validated['state'],
            'last_seen_at' => now(),
        ]);

        return response()->json(['message' => 'Status updated', 'device_id' => $device->id]);
    }

    public function command(Request $request)
    {
        $token = config('mqtt.api_token');

        if ($token && $request->bearerToken() !== $token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'device_uid' => 'required|string|max:255',
            'action' => 'required|in:on,off',
        ]);

        $device = Device::where('device_uid', $validated['device_uid'])->first();

        if ($device === null) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        $published = $this->mqttService->publishCommand($validated['device_uid'], $validated['action']);

        return response()->json([
            'message' => 'Command accepted',
            'published' => $published,
            'device_id' => $device->id,
        ]);
    }
}
