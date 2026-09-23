<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::whereHas('home', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('home')->latest()->get();

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        $this->authorize('create', Room::class);
        $homes = Home::where('user_id', auth()->id())->get();

        return view('rooms.create', compact('homes'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Room::class);

        $validated = $request->validate([
            'home_id' => 'required|exists:homes,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $home = Home::where('user_id', auth()->id())->findOrFail($validated['home_id']);

        Room::create([
            'home_id' => $home->id,
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('rooms.index')
            ->with('success', 'Room created successfully');
    }

    public function show(Room $room)
    {
        $this->authorize('view', $room);
        $room->load(['home', 'devices']);

        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $this->authorize('update', $room);
        $homes = Home::where('user_id', auth()->id())->get();

        return view('rooms.edit', compact('room', 'homes'));
    }

    public function update(Request $request, Room $room)
    {
        $this->authorize('update', $room);

        $validated = $request->validate([
            'home_id' => 'required|exists:homes,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $home = Home::where('user_id', auth()->id())->findOrFail($validated['home_id']);

        $room->update([
            'home_id' => $home->id,
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('rooms.index')
            ->with('success', 'Room updated successfully');
    }

    public function destroy(Room $room)
    {
        $this->authorize('delete', $room);
        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully');
    }
}
