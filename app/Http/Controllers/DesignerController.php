<?php

namespace App\Http\Controllers;

use App\Models\Designer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DesignerController extends Controller
{
    public function index(Request $request)
    {
        $showAll = $request->has('show_all');
        $designers = $showAll ? Designer::all() : Designer::where('active', true)->get();
        return view('designers.index', compact('designers', 'showAll'));
    }

    public function create()
    {
        return view('designers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'active' => 'required|in:1,0',
        ]);

        // Create a new User
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'designer',
        ]);
        // Create a new Designer
        Designer::create([
        'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'active' => $validated['active'],
        ]);

        return redirect()->route('designers.index')->with('success', 'Designer created successfully.');
    }

    public function show(Designer $designer)
    {
        return view('designers.show', compact('designer'));
    }

    public function edit(Designer $designer)
    {
        return view('designers.edit', compact('designer'));
    }

    public function update(Request $request, Designer $designer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $designer->user_id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update the associated User record
        $user = $designer->user;
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => !empty($validated['password']) ? Hash::make($validated['password']) : $user->password,
            'role' => 'designer',
            'is_active' => $request->input('active', 0), // Default to 0 if not provided
        ]);

        // Update the Designer record
        $designer->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'active' => $request->input('active', 0), // Default to 0 if not provided
        ]);

        return redirect()->route('designers.index')->with('success', 'Designer updated successfully.');
    }



    public function destroy(Designer $designer)
    {
        $designer->delete();

        return redirect()->route('designers.index')->with('success', 'Designer deleted successfully.');
    }
}