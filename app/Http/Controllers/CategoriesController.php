<?php

namespace App\Http\Controllers;

use App\Services\Categories\CategoriesService;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    public function __construct(
        private CategoriesService $service
    ) {
        $this->service = $service;
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
            is_featured: $request->query('is_featured'),
            published: $request->query('published')
        );
        return response()->json($this->service->getAll($params));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getBySlug(string $slug)
    {
        $category = $this->service->getbySlug($slug);
        if (!$category) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        }
        return response()->json($category);
    }
}
