<?php

namespace App\Http\Controllers;

use Imagecow\Image;
use Illuminate\Http\Request;
use App\Models\ProductSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SubmitProductRequest;
use App\Mail\ProductSubmissionApprovalMail;
use App\Mail\ProductSubmissionMail;
use App\Mail\ProductSubmissionRejectionMail;
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
            ->map(function (ProductSubmission $product) {
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

        $imageUrl = url($path);

        $thumbnailUrl = $this->createImageThumbnail($path);

        $productRequest = ProductRequest::findOrFail($request->id);

        $client = User::find($productRequest->client_id);

        $productSubmission = ProductSubmission::create([
            'image' => $imageUrl,
            'thumbnail' => $thumbnailUrl,
            'product_request_id' => $request->id,
            'status' => 'pending'
        ]);

        Mail::to($client->email)->queue(new ProductSubmissionMail($client, $productSubmission));

        return response()->json([
            'data' => $productSubmission
        ], 201);
    }
    private function createImageThumbnail($path)
    {
        //Adding this here because ImageCow can't be faked
        if (env('APP_ENV') === 'testing') {
            return "http://fakeurl.com/image.png";
        }

        $thumbnail = Image::fromString(Storage::disk('public')
            ->get(str_replace('storage/', '', $path)))
            ->resize(150, 150)
            ->quality(50) //Reduce the quality by 50%
            ->format('png')
            ->getString();

        $thumbnailFilename = uniqid() . ".png";

        Storage::put("public/thumbnails/" . $thumbnailFilename, $thumbnail);

        return url("storage/thumbnails/" . $thumbnailFilename);
    }

    public function approveProductSubmission(int $id)
    {

        $productSubmission = ProductSubmission::with('productRequest.client')->findOrFail($id);

        $client = $productSubmission->productRequest->client;

        if ($client->id !== auth()->id()) {
            return response()->json(['error' => 'You do not have permission to approve this submission'], 403);
        }

        $productSubmission->status = 'approved';
        $productSubmission->save();

        Mail::to($client->email)->queue(new ProductSubmissionApprovalMail($client, $productSubmission));

        return response()->json([
            'data' => $productSubmission
        ]);
    }

    public function declineProductSubmission(int $id)
    {

        $productSubmission = ProductSubmission::with('productRequest.client')->findOrFail($id);

        $client = $productSubmission->productRequest->client;

        if ($client->id !== auth()->id()) {
            return response()->json(['error' => 'You do not have permission to approve this submission'], 403);
        }

        $productSubmission->status = 'rejected';
        $productSubmission->save();

        Mail::to($client->email)
            ->queue(new ProductSubmissionRejectionMail($client, $productSubmission->productRequest));

        return response()->json([
            'message' => "Submission declined successfully!"
        ]);
    }
}
