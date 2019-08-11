<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($request->wantsJson() && !($e instanceof ValidationException)) {
            $message = 'An error has occurred on our server. We have already received notification about it and will soon begin to work on its solution.';
            $code = 500;
            if ($e instanceof HttpException) {
                $message = (string)$e->getMessage() ?: 'An error has occurred.';
                $code = $e->getStatusCode();
            } else if ($e instanceof ModelNotFoundException) {
                $message = 'Requested information not found.';
                $code = 404;
            }

            $response = [
                'message' => $message,
                'code' => $code,
            ];

            if (config('app.debug') == true) {
                $response['debug'] = [
                    'exception' => get_class($e),
                    'trace' => $e->getTrace(),
                    'message' => $e->getMessage()
                ];
            }

            return response()->json($response, $response['code']);
        }

        return parent::render($request, $e);
    }
}
