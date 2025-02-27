<?php

namespace App\Exceptions;

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
            //
        });
    }
    public function render($request, Throwable $exception)
    {
        if (get_class($exception) === 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException' && $request->expectsJson()) {
            return $this->errorResponse(getStatusCode('BASIC', 'NOT_FOUND'), getStatusCode('HTTP', 'NOT_FOUND'), 'Url not found');
        }
        if (get_class($exception) === 'Illuminate\Database\QueryException') {
            if(\Illuminate\Support\Facades\DB::transactionLevel()>0){
                \Illuminate\Support\Facades\DB::rollBack();
            }
        }
        return parent::render($request, $exception);
    }
}
