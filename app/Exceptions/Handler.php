<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
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
     * @param \Exception $exception
     */
    public function report(Exception $exception): void
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception): Response
    {
        if ($exception instanceof ModelNotFoundException) {
            return new JsonResponse([
                'status' => 'fail',
                'message' => 'Model not found.',
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        /** @var \Illuminate\Http\Response $response */
        $response = parent::render($request, $exception);

        $content = $response->getOriginalContent();

        if (\is_array($content)) {
            $content['status'] = 500 === $exception->getCode() ? 'error' : 'fail';
            $response->setContent(json_encode($content));
        }

        return $response;
    }
}
