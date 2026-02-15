<?php

namespace App\Http\Controllers;

use App\Models\BlogPosts;
use App\Services\BlogsPosts\BlogPostsServices;
use Illuminate\Http\Request;

class BlogPostsController extends Controller
{
    public function __construct(private BlogPostsServices $blogPostsServices)
    {
        $this->blogPostsServices = $blogPostsServices;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $params = new \App\Dto\ParamDto(
            page: $request->query('page', 1),
            perPage: $request->query('perPage', 10),
            search: $request->query('search'),
            sortBy: $request->query('sortBy'),
            sortDirection: $request->query('sortDirection'),
            is_featured: $request->query('is_featured')
        );
        $blogPosts = $this->blogPostsServices->getBlogPosts($params);
        return response()->json($blogPosts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPosts $blog_posts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPosts $blog_posts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogPosts $blog_posts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPosts $blog_posts)
    {
        //
    }
    public function getBlogBySlug($slug)
    {
        $blog = $this->blogPostsServices->getBlogBySlug($slug);
        return response()->json($blog);
    }
}
