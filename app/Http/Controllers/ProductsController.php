<?php

namespace App\Http\Controllers;

use App\Services\Products\GetProducts;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

    public function __construct(
        private GetProducts $getProducts
    ) {
        $this->getProducts = $getProducts;
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
            category_id: $request->query('category_id')
        );


        return response()->json($this->getProducts->execute($params));
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

    public function showBySlug(string $slug)
    {
        $getProductBySlug = app()->make(\App\Services\Products\GetProductsBySlug::class);
        return response()->json($getProductBySlug->execute($slug));
    }
}
