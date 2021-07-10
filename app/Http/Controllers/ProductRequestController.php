<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductSearchRequest;
use App\Http\Requests\StoreProductRequest;
use App\Models\ProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductRequestController extends Controller
{
    /**
     * Get all product requests
     *
     * @param  ProductSearchRequest  $request
     */
    public function getProductRequests(ProductSearchRequest $request): JsonResponse
    {

        $productRequests = ProductRequest::query()
            ->status($request->status)
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

        $productRequests = ProductRequest::query()
            ->status($request->status)
            ->where('client_id', $userId)
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
    public function getAcceptedProductRequests(ProductSearchRequest $request): JsonResponse
    {
        $userId = auth()->id();

        $productRequests = ProductRequest::query()
            ->status($request->status)
            ->where([
                'photographer_id' => $userId,
            ])
            ->simplePaginate(15);

        return response()->json([
            'data' => $productRequests
        ]);
    }


    /**
     * Store a newly created resource in storage.
     * @param  StoreProductRequest  $request
     */
    public function storeProductRequest(StoreProductRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $payload['client_id'] = auth()->id();

        //Used by photographer to uniquely identify a product at the processing facility
        $payload['reference_code'] = uniqid('product-');

        $payload['status'] = 'pending';

        $productRequest = ProductRequest::create($payload);

        return response()->json([
            'data' => $productRequest
        ]);
    }

    /**
     * Accept product request by photographer
     */
    public function acceptProductRequest(Request $request): JsonResponse
    {
        $productRequest = ProductRequest::findOrFail($request->id);

        if ($productRequest->status === 'accepted') {
            return response()->json([
                'message' => "You can no longer accept this product request"
            ], 409);
        }

        $productRequest->status = 'accepted';
        $productRequest->photographer_id = auth()->id();

        $productRequest->save();

        return response()->json([
            'message' => "Identify the product with your ref code at the processing facility",
            'data' => $productRequest
        ]);
    }
}
