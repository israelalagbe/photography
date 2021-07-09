<?php

namespace App\Http\Controllers;

use Imagecow\Image;
use Illuminate\Http\Request;
use App\Models\ProductSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SubmitProductRequest;
use App\Mail\ProductSubmissionMail;
use App\Models\ProductRequest;
use App\Models\User;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Mail;

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

        $thumbnailPath = "storage/images/" . uniqid() . ".png";

        $thumbnail = Image::fromFile($path);
        $thumbnail->resize(200, 200)
            ->format('png')
            ->save($thumbnailPath);

        $thumbnailUrl = url($thumbnailPath);

        $productRequest = ProductRequest::findOrFail($request->id);

        $client = User::find($productRequest->client_id);

        $productSubmission = ProductSubmission::create([
            'image' => $url,
            'thumbnail' => $thumbnailUrl,
            'product_request_id' => $request->id,
            'status' => 'pending'
        ]);

        Mail::to($client->email)->queue(new ProductSubmissionMail($client));

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
