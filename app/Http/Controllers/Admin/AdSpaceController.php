<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdSpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adSpaces = AdSpace::all();
        return view('admin.adspaces.index', compact('adSpaces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.adspaces.create');
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
            'position' => 'required|string|in:left,right',
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
            $filename = 'adspace_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/adspaces'), $filename);
            $imagePath = '/images/adspaces/' . $filename;
        }

        AdSpace::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'link' => $request->link,
            'position' => $request->position,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.adspaces.index')
            ->with('success', 'Ad Space created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdSpace $adspace)
    {
        return view('admin.adspaces.edit', compact('adspace'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdSpace $adspace)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|string|max:255',
            'position' => 'required|string|in:left,right',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle image upload
        $imagePath = $adspace->image;
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($adspace->image && file_exists(public_path($adspace->image))) {
                unlink(public_path($adspace->image));
            }
            
            $image = $request->file('image');
            $filename = 'adspace_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/adspaces'), $filename);
            $imagePath = '/images/adspaces/' . $filename;
        }

        $adspace->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'link' => $request->link,
            'position' => $request->position,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.adspaces.index')
            ->with('success', 'Ad Space updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdSpace $adspace)
    {
        // Delete image if it exists
        if ($adspace->image && file_exists(public_path($adspace->image))) {
            unlink(public_path($adspace->image));
        }
        
        $adspace->delete();

        return redirect()->route('admin.adspaces.index')
            ->with('success', 'Ad Space deleted successfully.');
    }
}