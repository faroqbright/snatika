<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $about = About::first();

        if (!$about) {
            $about = About::create([
                'heading' => '',
                'sub_heading' => '',
                'description' => '',
            ]);
        }

        return view('pages.about.index', compact('about'));
    }

    public function update(Request $request)
    {
        $about = About::find($request->id);
        $request->validate([
            'heading' => 'required|string|max:255',
            'sub_heading' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $about->update([
            'heading' => $request->heading,
            'sub_heading' => $request->sub_heading,
            'description' => $request->description,
        ]);

        return redirect()->route('about')->with('success', 'About details updated successfully.');
    }

}
