<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Display a listing of the content.
     */
    public function index(Request $request)
    {
        $query = Content::where('published', true);

        // Filter by type if provided
        if ($request->has('type') && in_array($request->type, ['tip', 'guide', 'seasonal'])) {
            $query->where('type', $request->type);
        }

        // Filter by search term if provided
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }

        $content = $query->latest()->paginate(9);

        return view('content.index', [
            'content' => $content,
            'type' => $request->type,
        ]);
    }

    /**
     * Display the specified content.
     */
    public function show($slug)
    {
        $content = Content::where('slug', $slug)
            ->where('published', true)
            ->with(['relatedProducts'])
            ->firstOrFail();

        // Get related content of the same type
        $relatedContent = Content::where('type', $content->type)
            ->where('id', '!=', $content->id)
            ->where('published', true)
            ->take(3)
            ->get();

        return view('content.show', [
            'content' => $content,
            'relatedContent' => $relatedContent,
        ]);
    }
}