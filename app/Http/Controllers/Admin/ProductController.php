<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::with('categories');
        
        // Search by name, SKU, or description
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sku', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Sort by column
        $sortColumn = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';
        
        // Validate sort column to prevent SQL injection
        $allowedColumns = ['id', 'name', 'price', 'stock', 'created_at'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'created_at';
        }
        
        $query->orderBy($sortColumn, $sortDirection === 'asc' ? 'asc' : 'desc');
        
        $products = $query->paginate(10)->withQueryString();
        
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:products',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
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
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $imageName);
            $imagePath = 'images/products/' . $imageName;
        }

        // Handle additional images upload
        $galleryImages = [];
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $imageName);
                $galleryImages[] = 'images/products/' . $imageName;
            }
        }

        // Create product
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'sku' => $request->sku,
            'stock' => $request->stock,
            'featured' => $request->has('featured'),
            'image' => $imagePath,
            'gallery_images' => $galleryImages ?: [], // Ensure gallery_images is always an array
        ]);

        // Attach categories
        $product->categories()->attach($request->categories);
        
        // Handle specifications
        if ($request->has('specs')) {
            foreach ($request->specs as $spec) {
                if (!empty($spec['name']) && !empty($spec['value'])) {
                    // Find or create the attribute
                    $attribute = Attribute::firstOrCreate(['name' => $spec['name']]);
                    
                    // Find or create the attribute value
                    $attributeValue = AttributeValue::firstOrCreate([
                        'attribute_id' => $attribute->id,
                        'value' => $spec['value']
                    ]);
                    
                    // Attach the attribute value to the product
                    $product->attributeValues()->attach($attributeValue->id);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $selectedCategories = $product->categories->pluck('id')->toArray();
        return view('admin.products.edit', compact('product', 'categories', 'selectedCategories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle image upload
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $imageName);
            $imagePath = 'images/products/' . $imageName;
        }

        // Handle additional images upload
        $galleryImages = is_array($product->gallery_images) ? $product->gallery_images : [];
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $imageName);
                $galleryImages[] = 'images/products/' . $imageName;
            }
        }

        // Handle removing selected images
        if ($request->has('remove_images') && is_array($request->remove_images)) {
            foreach ($request->remove_images as $imageToRemove) {
                // Remove from gallery_images array
                $galleryImages = array_filter($galleryImages, function($img) use ($imageToRemove) {
                    return $img !== $imageToRemove;
                });
                
                // Delete the file if it exists
                if (file_exists(public_path($imageToRemove))) {
                    unlink(public_path($imageToRemove));
                }
            }
        }
        
        // Update product
        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'sku' => $request->sku,
            'stock' => $request->stock,
            'featured' => $request->has('featured'),
            'image' => $imagePath,
            'gallery_images' => array_values($galleryImages ?: []), // Ensure gallery_images is always an array and reset array keys
        ]);

        // Sync categories
        $product->categories()->sync($request->categories);
        
        // Handle specifications
        if ($request->has('specs')) {
            // Get current attribute values
            $currentAttributeValueIds = $product->attributeValues()->pluck('attribute_value_id')->toArray();
            $newAttributeValueIds = [];
            
            foreach ($request->specs as $spec) {
                if (!empty($spec['name']) && !empty($spec['value'])) {
                    // Find or create the attribute
                    $attribute = Attribute::firstOrCreate(['name' => $spec['name']]);
                    
                    // Find or create the attribute value
                    $attributeValue = AttributeValue::firstOrCreate([
                        'attribute_id' => $attribute->id,
                        'value' => $spec['value']
                    ]);
                    
                    $newAttributeValueIds[] = $attributeValue->id;
                }
            }
            
            // Sync the attribute values
            $product->attributeValues()->sync($newAttributeValueIds);
        } else {
            // If no specs provided, detach all attribute values
            $product->attributeValues()->detach();
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // Get order items for this product with pagination
        $orderItems = $product->orderItems()->with('order.user')->latest()->paginate(10);
        
        // Get related products (products in the same categories)
        $relatedProducts = Product::whereHas('categories', function($query) use ($product) {
            $query->whereIn('categories.id', $product->categories->pluck('id'));
        })
        ->where('id', '!=', $product->id)
        ->take(5)
        ->get();
        
        return view('admin.products.show', compact('product', 'orderItems', 'relatedProducts'));
    }
    
    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete image if it exists
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        // Detach categories
        $product->categories()->detach();

        // Delete product
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}