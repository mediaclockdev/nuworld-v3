<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{

  /**
   * Render an exception into an HTTP response.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Exception  $exception
   * @return \Illuminate\Http\Response
   */
  public function render($request, Throwable $exception)
  {
    // Log the exception
    $this->logException($exception);

    if (config('app.env') === 'production') {
      // Return a custom JSON response
      return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
    }

    // In non-production environments, return the exception message
    if ($exception instanceof HttpException) {
      return response()->json([
        'success' => false,
        'message' => $exception->getMessage(),
      ], $exception->getStatusCode());
    } else {
      return response()->json([
        'success' => false,
        'message' => $exception->getMessage(),
      ], 500);
    }
  }

  /**
   * Log the exception.
   *
   * @param  \Exception  $exception
   * @return void
   */
  protected function logException(Exception $exception)
  {
    // Log the exception using Laravel's built-in logging
    logger()->error($exception->getMessage(), [
      'exception' => $exception,
      'request' => request()->all(),
    ]);
  }
}
