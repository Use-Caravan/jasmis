<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Route;
use Input;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
        //dd($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {                               
        // custom error message
        if($request->wantsJson()) {
            if($exception instanceof AuthenticationException) {
                return response()->json([
                    'message' => $exception->getMessage(),
                    'status' => UNAUTHORISED,
                    'time'=> time(),
                ], UNAUTHORISED);
            } else if ($exception instanceof \ErrorException) {        
                return response()->json([
                    'message' => $exception->getMessage(),
                    'status' => HTTP_ERROR,
                    'time'=> time(),
                ], HTTP_ERROR);                
            }
        }
        return parent::render($request, $exception);

        /* 
        if($this->isHttpException($exception)) {            
            switch ($exception->getStatusCode()) {
                
                case 400: //for Bad Request
                    return response()->view('partials.error',[],400);
                break;
                case 403: //for Forbidden
                    return response()->view('partials.error',[],403);
                break;
                case 404: //for Not Found
                    return response()->view('partials.error',[],404);
                break;                
                case '500': // internal server error
                    return response()->view('partials.error',[],500);    
                break;
                default:
                    return $this->renderHttpException($exception);
                break;
            }
        }         */
               
    }
}
