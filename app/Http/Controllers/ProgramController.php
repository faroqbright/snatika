<?php
namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::all();
        return view('pages.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('pages.programs.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'icon' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('icon')) {
            $imagePath = $request->file('icon')->store('images', 'public');
            $validated['icon'] = $imagePath;
        }

        Program::create($validated);
        return redirect()->route('programs.index')->with('success', 'Program created successfully!');
    }


    public function edit(Program $program)
    {
        return view('pages.programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('icon')) {
            if ($program->icon) {
                Storage::delete('public/' . $program->icon);
            }

            $validated['icon'] = $request->file('icon')->store('program_icons', 'public');
        }

        $program->update($validated);

        return redirect()->route('programs.index')->with('success', 'Program updated successfully!');
    }


    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('programs.index')->with('success', 'Program deleted successfully!');
    }
}
