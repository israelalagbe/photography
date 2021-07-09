<?php

namespace App\Mail;

use App\Models\ProductSubmission;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $productSubmission;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, ProductSubmission $productSubmission)
    {
        $this->user = $user;
        $this->productSubmission = $productSubmission;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.product_submission');
    }
}
