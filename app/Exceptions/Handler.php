<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            return response()->json(['data' => 'Oops, something went wrong'], 500);
        });
    }

    public function render($request, Throwable $e)
    {

        if (!$request->is('api/*')) {
            return parent::render($request, $e);
        }

        if ($e instanceof ValidationException) {

            return response()->json(['errors' => $e->errors()], 400);
        }

        return response()->json(['error' => $e->getMessage()], 500);
    }
}
