<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slideshow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SlideshowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slideshows = Slideshow::orderBy('order')->get();
        return view('admin.slideshows.index', compact('slideshows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.slideshows.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|string|max:255',
            'order' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'slideshow_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/slideshows'), $filename);
            $imagePath = '/images/slideshows/' . $filename;
        }

        Slideshow::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'link' => $request->link,
            'order' => $request->order,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.slideshows.index')
            ->with('success', 'Slideshow created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slideshow $slideshow)
    {
        return view('admin.slideshows.edit', compact('slideshow'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slideshow $slideshow)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|string|max:255',
            'order' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle image upload
        $imagePath = $slideshow->image;
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($slideshow->image && file_exists(public_path($slideshow->image))) {
                unlink(public_path($slideshow->image));
            }
            
            $image = $request->file('image');
            $filename = 'slideshow_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/slideshows'), $filename);
            $imagePath = '/images/slideshows/' . $filename;
        }

        $slideshow->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'link' => $request->link,
            'order' => $request->order,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.slideshows.index')
            ->with('success', 'Slideshow updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slideshow $slideshow)
    {
        // Delete image if it exists
        if ($slideshow->image && file_exists(public_path($slideshow->image))) {
            unlink(public_path($slideshow->image));
        }
        
        $slideshow->delete();

        return redirect()->route('admin.slideshows.index')
            ->with('success', 'Slideshow deleted successfully.');
    }
}