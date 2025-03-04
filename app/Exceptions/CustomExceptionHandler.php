<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class CustomExceptionHandler
{
    public function handle(Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Data not found',
                'errors' => $exception->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }

        // Form Exceptions
        if ($exception instanceof ValidationException) {

            return response()->json([
                'message' => $exception->getMessage(),
                'errors' => $exception->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Http Exceptions
        if ($exception instanceof HttpException) {
            $message = $exception->getMessage() ?? 'Http Exception!';
            if ($exception->getStatusCode() == Response::HTTP_UNAUTHORIZED) {
                $message = 'UNAUTHORIZED!';
            }

            if ($exception->getStatusCode() == Response::HTTP_FORBIDDEN) {
                $message = 'FORBIDDEN!';
            }
            return response()->json([
                'message' => $message,
            ], $exception->getStatusCode());
        }

        // Internal Exceptions

        if (
            $exception instanceof \Error
            || $exception instanceof \ErrorException
            || $exception instanceof QueryException
        ) {
            $response = ['message' => 'Internal error, please contact the server administrator!'];

            if (config('app.debug')) {
                if (!empty($exception->getMessage())) {
                    data_set($response, 'error', $exception->getMessage());
                }

                if (!empty($exception->getFile())) {
                    data_set($response, 'file', $exception->getFile());
                }

                if (!empty($exception->getLine())) {
                    data_set($response, 'line', $exception->getLine());
                }
            }

            $errorCodes = [
                Response::HTTP_INTERNAL_SERVER_ERROR,
                Response::HTTP_BAD_REQUEST,
            ];

            $status = (in_array($exception->getCode(), $errorCodes)) ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            return response()->json($response, $status);
        }
    }
}
