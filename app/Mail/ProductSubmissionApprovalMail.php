<?php

namespace App\Mail;

use App\Models\ProductSubmission;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductSubmissionApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $productSubmission;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $client, ProductSubmission $productSubmission)
    {
        $this->client = $client;
        $this->productSubmission = $productSubmission;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('mails.product_submission_approval');
    }
}
