<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitProductRequest;
use App\Models\ProductSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductSubmissionController extends Controller
{
    /**
     * Get all product requests
     *
     * @param  ProductSearchRequest  $request
     */
    public function getProductSubmissions(Request $request, int $id): JsonResponse
    {

        $status = $request->status;

        $productRequest = ProductSubmission::status($status)->where(['product_request_id' => $id])->simplePaginate();

        return response()->json([
            'data' => $productRequest
        ]);
    }

    public function submitProduct(SubmitProductRequest $request): JsonResponse
    {
        $image = $request->file('image');

        $path = Storage::putFile('public/images', $image);

        $url = url(Storage::url($path));
        

        return response()->json([
            'data' => null
        ]);
    }
}
