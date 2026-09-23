<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $schedules = Schedule::whereHas('device.room.home', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['device.room.home', 'user'])->latest()->paginate(20);

        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        $this->authorize('create', Schedule::class);
        $devices = auth()->user()->homes->flatMap->rooms->flatMap->devices;

        return view('schedules.create', compact('devices'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Schedule::class);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'device_id' => 'required|exists:devices,id',
            'action' => 'required|in:on,off',
            'scheduled_time' => 'required|date_format:H:i',
            'repeat_type' => 'required|in:once,daily,weekly',
            'is_active' => 'boolean',
        ]);

        $schedule = Schedule::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule created successfully');
    }

    public function show(Schedule $schedule)
    {
        $this->authorize('view', $schedule);
        $schedule->load(['device.room.home', 'user']);

        return view('schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $this->authorize('update', $schedule);
        $devices = auth()->user()->homes->flatMap->rooms->flatMap->devices;

        return view('schedules.edit', compact('schedule', 'devices'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'device_id' => 'required|exists:devices,id',
            'action' => 'required|in:on,off',
            'scheduled_time' => 'required|date_format:H:i',
            'repeat_type' => 'required|in:once,daily,weekly',
            'is_active' => 'boolean',
        ]);

        $schedule->update($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule updated successfully');
    }

    public function destroy(Schedule $schedule)
    {
        $this->authorize('delete', $schedule);
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule deleted successfully');
    }
}
