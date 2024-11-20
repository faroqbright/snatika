<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders=Slider::all();
        return view('pages.slider.index',compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
// return $request;
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'link' => 'required|url|max:255',
            'button_text' => 'required|string|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file
        ]);

        $imagePath = $request->file('image')->store('sliders', 'public');

        Slider::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'link' => $request->input('link'),
            'button_text' => $request->input('button_text'),
            'image' => $imagePath,
        ]);

        return redirect()->route('slider')->with('success', 'Slider saved successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $slider=Slider::findOrFail($id);
        return view('pages.slider.edit',compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'link' => 'required|url|max:255',
            'button_text' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
         ]);

        $slider = Slider::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($slider->image && \Storage::exists('public/' . $slider->image)) {
                \Storage::delete('public/' . $slider->image);
            }
            $imagePath = $request->file('image')->store('sliders', 'public');
            $slider->image = $imagePath;
        }
        $slider->title = $request->input('title');
        $slider->description = $request->input('description');
        $slider->link = $request->input('link');
        $slider->button_text = $request->input('button_text');
        $slider->save();

        return redirect()->route('slider')->with('success', 'Slider updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $slider=Slider::findOrFail($id);
        $slider->delete();
        return redirect()->route('slider')->with('success', 'Slider deleted successfully!');
    }

}
