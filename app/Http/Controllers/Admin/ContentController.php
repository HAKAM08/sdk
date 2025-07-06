<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    /**
     * Display a listing of the content.
     */
    public function index()
    {
        $content = Content::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.content.index', compact('content'));
    }

    /**
     * Show the form for creating new content.
     */
    public function create()
    {
        $products = Product::all();
        return view('admin.content.create', compact('products'));
    }

    /**
     * Store a newly created content in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:tip,guide,seasonal',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'related_products' => 'nullable|array',
            'related_products.*' => 'exists:products,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/content'), $imageName);
            $imagePath = 'images/content/' . $imageName;
        }

        // Create content
        $content = Content::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'featured_image' => $imagePath,
            'type' => $request->type,
            'published' => true, // Always publish new content
            'user_id' => Auth::id(),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        // Sync related products
        if ($request->has('related_products')) {
            $content->relatedProducts()->sync($request->related_products);
        }

        return redirect()->route('admin.content.index')
            ->with('success', 'Content created successfully.');
    }

    /**
     * Display the specified content.
     */
    public function show(Content $content)
    {
        return view('admin.content.show', compact('content'));
    }

    /**
     * Show the form for editing the specified content.
     */
    public function edit(Content $content)
    {
        $products = Product::all();
        $selectedProducts = $content->relatedProducts->pluck('id')->toArray();
        return view('admin.content.edit', compact('content', 'products', 'selectedProducts'));
    }

    /**
     * Update the specified content in storage.
     */
    public function update(Request $request, Content $content)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:tip,guide,seasonal',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'related_products' => 'nullable|array',
            'related_products.*' => 'exists:products,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle image upload
        $imagePath = $content->featured_image;
        if ($request->hasFile('featured_image')) {
            // Delete old image if it exists
            if ($content->featured_image && file_exists(public_path($content->featured_image))) {
                unlink(public_path($content->featured_image));
            }
            
            $image = $request->file('featured_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/content'), $imageName);
            $imagePath = 'images/content/' . $imageName;
        }

        // Update content
        $content->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'featured_image' => $imagePath,
            'type' => $request->type,
            'published' => true, // Always keep content published
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        // Sync related products
        if ($request->has('related_products')) {
            $content->relatedProducts()->sync($request->related_products);
        } else {
            $content->relatedProducts()->detach();
        }

        return redirect()->route('admin.content.index')
            ->with('success', 'Content updated successfully.');
    }

    /**
     * Remove the specified content from storage.
     */
    public function destroy(Content $content)
    {
        // Delete image if it exists
        if ($content->featured_image && file_exists(public_path($content->featured_image))) {
            unlink(public_path($content->featured_image));
        }

        // Detach related products
        $content->relatedProducts()->detach();

        // Delete content
        $content->delete();

        return redirect()->route('admin.content.index')
            ->with('success', 'Content deleted successfully.');
    }
}