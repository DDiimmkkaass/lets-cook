<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * @param  \Exception $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        if (!in_array((int) $e->getCode(), [404, 422])) {
            if (config('mail.from.address')) {
                $_request = request()->all();
        
                $debugSetting = config('app.debug');
        
                config('app.debug', true);
                if (ExceptionHandler::isHttpException($e)) {
                    $content = ExceptionHandler::toIlluminateResponse(ExceptionHandler::renderHttpException($e), $e);
                } else {
                    $content = ExceptionHandler::toIlluminateResponse(ExceptionHandler::convertExceptionToResponse($e), $e);
                }
        
                config('app.debug', $debugSetting);
        
                $content = (!isset($content->original)) ? $e->getMessage() : $content->original;
        
                admin_notify(
                    'message: '.$e->getMessage().', line: '.$e->getLine().', file: '.$e->getFile(),
                    [
                        'url'     => request()->fullUrl(),
                        'request' => $_request,
                        'content' => $content,
                    ]
                );
            }
        }
        
        return parent::report($e);
    }
    
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($this->isHttpException($e) && env('HANDLE_ERROR', true)) {
            $statusCode = $e->getStatusCode();
            
            switch ($statusCode) {
                case 404:
                    return redirect(route('not_found'), 301);
            }
        }
        
        return parent::render($request, $e);
    }
}
