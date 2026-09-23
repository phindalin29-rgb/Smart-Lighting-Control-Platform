<?php

namespace App\Http\Controllers;

use App\Models\Home;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $homes = Home::where('user_id', auth()->id())->with('rooms')->latest()->get();

        return view('homes.index', compact('homes'));
    }

    public function create()
    {
        $this->authorize('create', Home::class);

        return view('homes.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Home::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        Home::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('homes.index')
            ->with('success', 'Home created successfully');
    }

    public function show(Home $home)
    {
        $this->authorize('view', $home);
        $home->load('rooms');

        return view('homes.show', compact('home'));
    }

    public function edit(Home $home)
    {
        $this->authorize('update', $home);

        return view('homes.edit', compact('home'));
    }

    public function update(Request $request, Home $home)
    {
        $this->authorize('update', $home);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $home->update($validated);

        return redirect()->route('homes.index')
            ->with('success', 'Home updated successfully');
    }

    public function destroy(Home $home)
    {
        $this->authorize('delete', $home);
        $home->delete();

        return redirect()->route('homes.index')
            ->with('success', 'Home deleted successfully');
    }
}
