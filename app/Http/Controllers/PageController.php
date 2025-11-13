<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('created_at', 'desc')->get();
        return response()->json($pages);
    }

    public function show(Page $page)
    {
        return response()->json($page);
    }

    public function showBySlug($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('user.page', compact('page'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages',
            'content' => 'required|string',
            'status' => 'required|in:published,draft',
            'published_at' => 'nullable|date'
        ]);

        $page = Page::create($validated);
        return response()->json($page, 201);
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'required|string',
            'status' => 'required|in:published,draft',
            'published_at' => 'nullable|date'
        ]);

        $page->update($validated);
        return response()->json($page);
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return response()->json(['message' => 'Page deleted successfully']);
    }

    public function toggleStatus(Page $page)
    {
        $page->status = $page->status === 'published' ? 'draft' : 'published';
        $page->save();
        return response()->json($page);
    }
}
