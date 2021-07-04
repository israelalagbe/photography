<?php

namespace App\Http\Controllers;

use Imagecow\Image;
use Illuminate\Http\Request;
use App\Models\ProductSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SubmitProductRequest;
use App\Traits\UploadTrait;

class ProductSubmissionController extends Controller
{
    use UploadTrait;
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

        $path = $this->handleUpload($image, 'images');

        $url = url($path);

        $image = Image::fromFile($path);
        $image->resize(200, 200);
        //Saving thumbnail as base64 because it's not large
        $thumbnailBase64 = $image->base64();



        $productSubmission = ProductSubmission::create([
            'image' => $url,
            'thumbnail' => $thumbnailBase64,
            'product_requests_id' => $request->id,
            'status' => 'pending'
        ]);

        return response()->json([
            'data' => $productSubmission
        ]);
    }
}
