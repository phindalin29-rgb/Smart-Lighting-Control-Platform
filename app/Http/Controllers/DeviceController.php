<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Room;
use App\Services\DeviceControlService;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function __construct(private DeviceControlService $deviceControlService) {}

    public function index()
    {
        $devices = Device::whereHas('room.home', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('room.home')->latest()->get();

        return view('devices.index', compact('devices'));
    }

    public function create()
    {
        $this->authorize('create', Device::class);
        $rooms = Room::whereHas('home', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('home')->get();

        return view('devices.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Device::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'device_uid' => 'required|string|unique:devices,device_uid|max:255',
            'room_id' => 'required|exists:rooms,id',
            'type' => 'required|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'mac_address' => 'nullable|string|max:255',
        ]);

        $room = Room::whereHas('home', function ($query) {
            $query->where('user_id', auth()->id());
        })->findOrFail($validated['room_id']);

        Device::create([
            ...$validated,
            'room_id' => $room->id,
        ]);

        return redirect()->route('devices.index')
            ->with('success', 'Device created successfully');
    }

    public function show(Device $device)
    {
        $this->authorize('view', $device);
        $device->load(['room.home', 'states']);

        return view('devices.show', compact('device'));
    }

    public function edit(Device $device)
    {
        $this->authorize('update', $device);
        $rooms = Room::whereHas('home', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('home')->get();

        return view('devices.edit', compact('device', 'rooms'));
    }

    public function update(Request $request, Device $device)
    {
        $this->authorize('update', $device);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'device_uid' => 'required|string|unique:devices,device_uid,'.$device->id,
            'room_id' => 'required|exists:rooms,id',
            'type' => 'required|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'mac_address' => 'nullable|string|max:255',
        ]);

        $room = Room::whereHas('home', function ($query) {
            $query->where('user_id', auth()->id());
        })->findOrFail($validated['room_id']);

        $device->update([
            ...$validated,
            'room_id' => $room->id,
        ]);

        return redirect()->route('devices.index')
            ->with('success', 'Device updated successfully');
    }

    public function destroy(Device $device)
    {
        $this->authorize('delete', $device);
        $device->delete();

        return redirect()->route('devices.index')
            ->with('success', 'Device deleted successfully');
    }

    public function status()
    {
        $devices = Device::whereHas('room.home', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('room.home')->get()->map(function ($device) {
            return [
                'id' => $device->id,
                'name' => $device->name,
                'device_uid' => $device->device_uid,
                'type' => $device->type,
                'room' => $device->room->name ?? null,
                'home' => $device->room->home->name ?? null,
                'status' => $device->status ? 'online' : 'offline',
                'current_state' => $device->current_state ? 'on' : 'off',
                'last_seen_at' => $device->last_seen_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'devices' => $devices,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    public function deviceStatus(Device $device)
    {
        $this->authorize('view', $device);
        $device->load('room.home');

        return response()->json([
            'device' => [
                'id' => $device->id,
                'name' => $device->name,
                'device_uid' => $device->device_uid,
                'type' => $device->type,
                'room' => $device->room->name ?? null,
                'home' => $device->room->home->name ?? null,
                'status' => $device->status ? 'online' : 'offline',
                'current_state' => $device->current_state ? 'on' : 'off',
                'last_seen_at' => $device->last_seen_at?->toIso8601String(),
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    public function turnOn(Device $device)
    {
        $this->authorize('update', $device);
        $this->deviceControlService->applyAction($device, 'on', 'manual', auth()->user());

        return back()->with('success', 'Device turned ON');
    }

    public function turnOff(Device $device)
    {
        $this->authorize('update', $device);
        $this->deviceControlService->applyAction($device, 'off', 'manual', auth()->user());

        return back()->with('success', 'Device turned OFF');
    }

    public function control(Request $request, Device $device)
    {
        $this->authorize('update', $device);
        $validated = $request->validate([
            'action' => 'required|in:on,off',
        ]);

        $this->deviceControlService->applyAction($device, $validated['action'], 'manual', auth()->user());

        return back()->with('success', 'Device turned '.strtoupper($validated['action']));
    }
}
