<?php

namespace App\Http\Controllers;

use App\Models\AdSpace;
use App\Models\Category;
use App\Models\Content;
use App\Models\Product;
use App\Models\Review;
use App\Models\Slideshow;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        $featuredProducts = Product::where('featured', true)
            ->with('categories')
            ->take(8)
            ->get();

        $latestProducts = Product::with('categories')
            ->latest()
            ->take(8)
            ->get();

        $mainCategories = Category::whereNull('parent_id')
            ->with('children')
            ->take(6)
            ->get();

        $latestContent = Content::where('published', true)
            ->latest()
            ->take(3)
            ->get();
            
        // Get active slideshows ordered by their position
        $slideshows = Slideshow::active()
            ->ordered()
            ->get();
            
        // Get active ad spaces
        $leftAdSpace = AdSpace::active()
            ->position('left')
            ->first();
            
        $rightAdSpace = AdSpace::active()
            ->position('right')
            ->first();
            
        // Get positive reviews (4+ stars)
        $positiveReviews = Review::where('rating', '>=', 4)
            ->with(['user', 'product'])
            ->latest()
            ->take(3)
            ->get();

        return view('home', [
            'featuredProducts' => $featuredProducts,
            'latestProducts' => $latestProducts,
            'mainCategories' => $mainCategories,
            'latestContent' => $latestContent,
            'slideshows' => $slideshows,
            'leftAdSpace' => $leftAdSpace,
            'rightAdSpace' => $rightAdSpace,
            'positiveReviews' => $positiveReviews,
        ]);
    }
}