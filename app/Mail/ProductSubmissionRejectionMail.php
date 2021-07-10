<?php

namespace App\Mail;

use App\Models\ProductRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductSubmissionRejectionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $productRequest;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $client, ProductRequest $productRequest)
    {
        $this->client = $client;
        $this->productRequest = $productRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.product_submission_rejection');
    }
}
