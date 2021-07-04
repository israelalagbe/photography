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
     * Get all product submissions
     *
     */
    public function getProductSubmissions(Request $request, int $id): JsonResponse
    {

        $status = $request->status;
        $query = ProductSubmission::status($status)
            ->where(['product_request_id' => $id])
            ->orderBy('id', 'desc');

        //Hide the full size image from the submissions that has not been approved
        $productSubmissions = $query->get()
            ->map(function ($product) {
                if ($product->status !== 'approved') {
                    unset($product->image);
                }
                return $product;
            });

        return response()->json([
            'data' => $productSubmissions
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
            'product_request_id' => $request->id,
            'status' => 'pending'
        ]);

        return response()->json([
            'data' => $productSubmission
        ]);
    }
    public function acceptProductSubmission(Request $request, int $id)
    {
        $productSubmission = ProductSubmission::findOrFail($id);
        $productSubmission->status = 'approved';
        $productSubmission->save();

        return response()->json([
            'data' => $productSubmission
        ]);
    }
}
