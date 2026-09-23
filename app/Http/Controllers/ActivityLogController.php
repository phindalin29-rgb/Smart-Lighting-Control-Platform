<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $ownedDeviceIds = $user->homes()
            ->whereHas('rooms.devices')
            ->get()
            ->flatMap->rooms
            ->flatMap->devices
            ->modelKeys();

        $logs = ActivityLog::with('device.room.home')
            ->where(function ($query) use ($user, $ownedDeviceIds) {
                $query->where('user_id', $user->id);

                if ($ownedDeviceIds !== []) {
                    $query->orWhereIn('device_id', $ownedDeviceIds);
                }
            })
            ->latest()
            ->paginate(20);

        return view('activity-log.index', compact('logs'));
    }
}
