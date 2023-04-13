<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Spatie\Permission\Exceptions\UnauthorizedException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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

    public function render($request, Throwable $e)
    {
        if ($e instanceof UnauthorizedException) {
            $data = [
                "success"=>false,
                "message"=> "User does not have the right roles"
            ];
            return response()->json($data, 403);
        }

        return parent::render($request, $e);
    }
    // public function render($request, Exception $exception)
    // {
    //     // This will replace our 404 response with
    //     // a JSON response.
    //     if ($exception instanceof ModelNotFoundException &&
    //         $request->wantsJson())
    //     {
    //         return response()->json([
    //             'data' => 'Resource not found'
    //         ], 404);
    //     }

    //     return parent::render($request, $exception);
    // }

    // protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     return response()->json(['error' => 'Unauthenticated'], 401);
    // }


}
