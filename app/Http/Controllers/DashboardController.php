<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Schedule;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $homes = $user->homes()->with('rooms.devices')->get();
        $devices = $homes->flatMap->rooms->flatMap->devices;

        $totalDevices = $devices->count();
        $onlineDevices = $devices->where('status', true)->count();
        $lightsOn = $devices->where('current_state', true)->count();
        $lightsOff = $totalDevices - $lightsOn;
        $ownedDeviceIds = $devices->pluck('id')->toArray();

        $recentActivity = ActivityLog::with('device.room')
            ->where(function ($query) use ($user, $ownedDeviceIds) {
                $query->where('user_id', $user->id);
                if ($ownedDeviceIds !== []) {
                    $query->orWhereIn('device_id', $ownedDeviceIds);
                }
            })
            ->latest()
            ->take(8)
            ->get();

        $energyUsage = $totalDevices > 0 ? round(($lightsOn / $totalDevices) * 100, 1) : 0;

        $brightness = $totalDevices > 0 ? round(($onlineDevices / $totalDevices) * 100, 1) : 0;

        $weeklyActivity = ActivityLog::where(function ($query) use ($user, $ownedDeviceIds) {
            $query->where('user_id', $user->id);
            if ($ownedDeviceIds !== []) {
                $query->orWhereIn('device_id', $ownedDeviceIds);
            }
        })
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->get()
            ->groupBy(function ($log) {
                return $log->created_at->locale('en')->isoFormat('ddd');
            })
            ->map(function ($logs) {
                return $logs->count();
            });

        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $chartData = collect($days)->map(function ($day) use ($weeklyActivity) {
            return [
                'day' => $day,
                'count' => $weeklyActivity->get($day, 0),
            ];
        });

        $schedules = Schedule::whereHas('device.room.home', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['device.room.home', 'user'])->latest()->get();

        $deviceStates = $devices->map(function ($device) {
            return [
                'id' => $device->id,
                'name' => $device->name,
                'room' => $device->room->name ?? 'N/A',
                'home' => $device->room->home->name ?? 'N/A',
                'status' => $device->status ? 'online' : 'offline',
                'current_state' => $device->current_state ? 'on' : 'off',
            ];
        });

        return view('dashboard', compact(
            'homes',
            'devices',
            'totalDevices',
            'onlineDevices',
            'lightsOn',
            'lightsOff',
            'recentActivity',
            'energyUsage',
            'brightness',
            'chartData',
            'schedules',
            'deviceStates',
        ));
    }
}
