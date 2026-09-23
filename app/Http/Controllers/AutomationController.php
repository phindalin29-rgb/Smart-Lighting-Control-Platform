<?php

namespace App\Http\Controllers;

use App\Models\AutomationRule;
use Illuminate\Http\Request;

class AutomationController extends Controller
{
    public function index()
    {
        $rules = AutomationRule::whereHas('device.room.home', function ($query) {
            $query->where('user_id', auth()->id());
        })->with(['device.room.home', 'user'])->latest()->paginate(20);

        return view('automation.index', compact('rules'));
    }

    public function create()
    {
        $this->authorize('create', AutomationRule::class);
        $devices = auth()->user()->homes->flatMap->rooms->flatMap->devices;

        return view('automation.create', compact('devices'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', AutomationRule::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'device_id' => 'required|exists:devices,id',
            'condition_type' => 'required|in:time,device_state',
            'condition_value' => 'nullable|string|max:255',
            'action' => 'required|in:on,off',
            'is_active' => 'boolean',
        ]);

        AutomationRule::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('automation.index')
            ->with('success', 'Automation rule created successfully');
    }

    public function show(AutomationRule $automation)
    {
        $this->authorize('view', $automation);
        $automation->load(['device.room.home', 'user']);

        return view('automation.show', compact('automation'));
    }

    public function edit(AutomationRule $automation)
    {
        $this->authorize('update', $automation);
        $devices = auth()->user()->homes->flatMap->rooms->flatMap->devices;

        return view('automation.edit', compact('automation', 'devices'));
    }

    public function update(Request $request, AutomationRule $automation)
    {
        $this->authorize('update', $automation);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'device_id' => 'required|exists:devices,id',
            'condition_type' => 'required|in:time,device_state',
            'condition_value' => 'nullable|string|max:255',
            'action' => 'required|in:on,off',
            'is_active' => 'boolean',
        ]);

        $automation->update($validated);

        return redirect()->route('automation.index')
            ->with('success', 'Automation rule updated successfully');
    }

    public function destroy(AutomationRule $automation)
    {
        $this->authorize('delete', $automation);
        $automation->delete();

        return redirect()->route('automation.index')
            ->with('success', 'Automation rule deleted successfully');
    }
}
