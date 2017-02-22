<?php

namespace App\Exceptions;

use Exception;
use FlashMessages;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Validation\ValidationException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Handler
 * @package App\Exceptions
 */
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
        TokenMismatchException::class,
        AuthenticationException::class,
        NotFoundHttpException::class,
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
        $this->_sendToAdmin($e);
        
        return parent::report($e);
    }
    
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $e
     *
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        if ($this->isHttpException($e)) {
            $statusCode = $e->getStatusCode();
        
            if (env('HANDLE_ERROR', true)) {
                switch ($statusCode) {
                    case 404:
                        return redirect(route('not_found'), 302);
                    default:
                        if (is_front()) {
                            return redirect(route('server_error'), 302);
                        }
                }
            }
        }
    
        if ($e instanceof TokenMismatchException) {
            FlashMessages::add('error', trans('front_messages.session expired, please reload the page'));
        
            return redirect()->back();
        }
    
        return parent::render($request, $e);
    }
    
    /**
     * @param Exception $e
     */
    private function _sendToAdmin(Exception $e)
    {
        try {
            if (config('mail.from.address')) {
                $_request = request()->all();
                
                $debugSetting = config('app.debug');
                
                config('app.debug', true);
                
                if (ExceptionHandler::isHttpException($e)) {
                    $content = ExceptionHandler::toIlluminateResponse(ExceptionHandler::renderHttpException($e), $e);
                } else {
                    $content = ExceptionHandler::toIlluminateResponse(
                        ExceptionHandler::convertExceptionToResponse($e),
                        $e
                    );
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
        } catch (Exception $e) {
            // nonsense, i do not know what to do
        }
    }
}
