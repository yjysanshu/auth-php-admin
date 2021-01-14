<?php

namespace App\Exceptions;

use App\Http\Common\Helper\FormatHelper;
use App\Http\Common\Helper\LogHelper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            if ($exception->getCode() != -10000) {      //Exception -10000 不记录到sentry
                LogHelper::alarm($exception);
                app('sentry')->captureException($exception);
            }
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UserException || $exception instanceof CodeException) {
            return FormatHelper::failCode($exception->getCode(), $exception->getMessage());
        } else {
            //监控不存在的接口调用
            if ($exception instanceof NotFoundHttpException) {
                LogHelper::warning('uri 不存在: ' . $request->path() . ' 调用IP: ' . getIp(), [$request->all()]);
            }

            if (env('APP_ENV') == 'production') {
                $data = [
                    'code' => $exception->getCode(),
                    'trace' => $exception->getTrace()
                ];
                LogHelper::warning($exception->getMessage(), [
                    $exception->getFile(),
                    $exception->getLine(),
                    $data
                ]);
                return FormatHelper::fail();
            } else {
                return parent::render($request, $exception);
            }
        }
    }
}
