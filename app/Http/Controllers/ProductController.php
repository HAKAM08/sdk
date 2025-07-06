<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['categories']);

        // Filter by category if provided
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->whereHas('categories', function ($q) use ($category) {
                    $q->where('categories.id', $category->id);
                });
            }
        }

        // Filter by search term if provided
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('short_description', 'like', "%{$searchTerm}%");
            });
        }

        // Sort products
        $sortBy = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';
        $allowedSortFields = ['name', 'price', 'created_at'];
        
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        $products = $query->paginate(12);

        return view('products.index', [
            'products' => $products,
            'categories' => Category::whereNull('parent_id')->with('children')->get(),
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['categories', 'attributeValues.attribute', 'reviews' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->firstOrFail();

        // Get related products from the same categories
        $relatedProducts = Product::whereHas('categories', function ($query) use ($product) {
            $query->whereIn('categories.id', $product->categories->pluck('id'));
        })
        ->where('id', '!=', $product->id)
        ->take(4)
        ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    /**
     * Display products by category.
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        // Get all descendant categories (if any)
        $categoryIds = [$category->id];
        if ($category->children->count() > 0) {
            $this->addChildCategoryIds($category, $categoryIds);
        }
        
        $products = Product::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })->paginate(12);

        return view('products.category', [
            'category' => $category,
            'products' => $products,
            'categories' => Category::whereNull('parent_id')->with('children')->get(),
        ]);
    }

    /**
     * Recursively add child category IDs to the array.
     */
    private function addChildCategoryIds(Category $category, array &$categoryIds): void
    {
        foreach ($category->children as $child) {
            $categoryIds[] = $child->id;
            if ($child->children->count() > 0) {
                $this->addChildCategoryIds($child, $categoryIds);
            }
        }
    }
}