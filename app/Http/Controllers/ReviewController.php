<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, $productId)
    {
        // Validate the request
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // Find the product
        $product = Product::findOrFail($productId);

        // Create the review
        $review = new Review([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'approved', // Auto-approve for now, could be 'pending' for moderation
        ]);

        $review->save();

        return redirect()->back()->with('success', 'Your review has been submitted successfully!');
    }
}