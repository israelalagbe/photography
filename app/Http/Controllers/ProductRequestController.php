<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductSearchRequest;
use App\Models\Product;
use App\Models\ProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;

class ProductRequestController extends Controller
{
    /**
     * Get all product requests
     *
     * @param  ProductSearchRequest  $request
     */
    public function getProductRequests(ProductSearchRequest $request): JsonResponse
    {
        $status = $request->status;

        $productRequests = ProductRequest::query()
            ->when($status, function ($query) use ($status) {
                return $query->where('status', '=', $status);
            })
            ->simplePaginate(15);

        return response()->json([
            'data' => $productRequests
        ]);
    }

    /**
     * Get all products requested by a client
     *
     * @param  ProductSearchRequest  $request
     */
    public function getClientProductRequests(ProductSearchRequest $request): JsonResponse
    {
        $userId = auth()->id();
        $status = $request->status;

        $productRequests = ProductRequest::query()
            ->when($status, function ($query) use ($status) {
                return $query->where('status', '=', $status);
            })
            ->where('user_id', $userId)
            ->simplePaginate(15);

        return response()->json([
            'data' => $productRequests
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeProductRequests(Request $request): Response
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function showProduct(Product $product)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function updateProduct(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function deleteProduct(Product $product)
    {
    }
}
