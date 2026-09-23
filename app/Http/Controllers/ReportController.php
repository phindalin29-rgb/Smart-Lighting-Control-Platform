<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $homes = $user->homes()->with('rooms.devices')->get();
        $devices = $homes->flatMap->rooms->flatMap->devices;

        $totalDevices = $devices->count();
        $lightsOn = $devices->where('current_state', true)->count();
        $onlineDevices = $devices->where('status', true)->count();
        $lightsOff = $totalDevices - $lightsOn;

        $activityLogs = ActivityLog::with('device.room')
            ->where(function ($query) use ($user, $devices) {
                $query->where('user_id', $user->id);
                $deviceIds = $devices->pluck('id')->toArray();
                if ($deviceIds !== []) {
                    $query->orWhereIn('device_id', $deviceIds);
                }
            })
            ->latest()
            ->take(20)
            ->get();

        $schedules = Schedule::whereHas('device.room.home', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['device.room.home', 'user'])->latest()->get();

        $weeklyActivity = ActivityLog::where(function ($query) use ($user, $devices) {
            $query->where('user_id', $user->id);
            $deviceIds = $devices->pluck('id')->toArray();
            if ($deviceIds !== []) {
                $query->orWhereIn('device_id', $deviceIds);
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

        return view('reports.index', compact(
            'totalDevices',
            'lightsOn',
            'lightsOff',
            'onlineDevices',
            'activityLogs',
            'schedules',
            'chartData',
            'homes',
            'devices',
        ));
    }
}
