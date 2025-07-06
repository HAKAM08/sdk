<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')->with(['children'])->get();

        return view('categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Display the specified category.
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        return view('categories.show', [
            'category' => $category,
        ]);
    }
}