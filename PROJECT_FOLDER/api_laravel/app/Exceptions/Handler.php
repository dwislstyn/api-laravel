<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use stdClass;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        $resultOutput = new stdClass();
        $resultOutput->responseCode = $exception instanceof ExceptionInterface ? str_pad(strval($exception->getErrorCode()), 2, '0', STR_PAD_LEFT) : '99';
        $resultOutput->responseDesc = ($exception instanceof ExceptionInterface ? $exception->getMessage() : (ENV('APP_DEBUG') === true ? $exception->getMessage().' '.$exception->getFile().' Ln.'.$exception->getLine() : 'Terjadi kesalahan, mohon coba beberapa saat lagi yaa...'));

        return response()->json($resultOutput, 200);
    }
}
