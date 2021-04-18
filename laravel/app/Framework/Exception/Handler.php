<?php

namespace App\Framework\Exception;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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

    protected function renderHttpException(HttpExceptionInterface $exception)
    {
        if (app()->environment('testing')) {
            return response()->view(
                'errors.500',
                [
                    'stackTrace' => $exception->getTraceAsString(),
                    'error' => $exception->getMessage()
                ],
                $exception->getStatusCode()
            );
        }

        return parent::renderHttpException($exception);
    }
}
