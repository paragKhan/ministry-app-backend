<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = null;

        if (isUser()) {
            $blogs = Blog::all();
        } else if (isAdmin()) {
            $blogs = Blog::simplePaginate(20);
        }

        return response()->json($blogs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBlogRequest $request)
    {
        $blog = Blog::create($request->validated());

        if ($request->has('photo')) {
            $blog->addMediaFromRequest('photo')->toMediaCollection('photo');
        }

        return response()->json($blog);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        return response()->json($blog->load('comments', 'reactions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $blog->update($request->validated());

        if ($request->has('photo')) {
            $blog->clearMediaCollection('photo');
            $blog->addMediaFromRequest('photo')->toMediaCollection('photo');
        }

        return response()->json($blog);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return response()->json(['message' => 'Blog deleted']);
    }

    public function toggleReact(Blog $blog)
    {
        $reaction = $blog->reactions()->where('user_id', auth()->id())->first();
        if ($reaction) {
            $reaction->delete();
            return false;
        } else {
            $blog->reactions()->create(['user_id' => auth()->id()]);
            return true;
        }
    }
}
