<?php

namespace App\Exceptions;

use GuzzleHttp\Exception\ServerException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class,
        CommandNotFoundException::class,
        BaseHttpException::class,
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

    protected $dontReportOnLocal = [
        BindingResolutionException::class,
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

        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                    'code' => 401,
                ], 401);
            }
        });
    }

    public function render($request, Throwable $exception)
    {
        // jika debug di matikan maka trace error tidak akan di tampilkan di di render response eror
        if (!config('app.debug')) {
            if ($exception instanceof MethodNotAllowedHttpException) {
                $exception = new BaseException(405, 'Method Not Allowed');
            } elseif ($exception instanceof ModelNotFoundException) {
                $exception = new BaseException(404, 'Data tidak ditemukan', [
                    'trace' => $exception->getMessage(),
                ]);
            } elseif ($exception instanceof NotFoundHttpException) {
                $exception = new BaseException(400, 'Route Not Found');
            } elseif ($exception instanceof ServerException) {
                $exception = new BaseException(500, 'Terjadi kesalahan. Mohon ulangi kembali');
            }

            if ($exception instanceof AuthorizationException) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
        }


        if ($exception instanceof ValidationException) {
            $exception = new BaseException(
                422,
                $exception->validator->errors()->first(),
                null,
                [
                    'status' => false,
                    'data' => [
                        'message' => $exception->validator->errors()->first(),
                    ],
                ]
            );
        }

        return parent::render($request, $exception);
    }

    public function isLocalEnv()
    {
        return app()->environment('local');
    }

    public function shouldReportOnLocal(Throwable $e)
    {
        return $this->isLocalEnv() && !$this->shouldReportOnLocal($e);
    }

    public function shouldntReportOnLocal(Throwable $e)
    {
        foreach ($this->dontReportOnLocal as $type) {
            if ($e instanceof $type) {
                return true;
            }
        }

        return false;
    }
}
