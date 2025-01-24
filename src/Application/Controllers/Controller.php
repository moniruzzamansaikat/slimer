<?php

declare(strict_types=1);

namespace App\Application\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Response as SlimResponse;


abstract class Controller
{
    /**
     * Helper method to respond with data.
     *
     * @param mixed $data
     * @param int $status
     * @return Response
     */
    protected function respondWithData($data, int $status = 200): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($status);
    }

    /**
     * Helper method to respond with an error.
     *
     * @param string $error
     * @param int $status
     * @return Response
     */
    protected function respondWithError(string $error, int $status = 400): Response
    {
        $response = new SlimResponse();
        $errorData = ['error' => $error];
        $response->getBody()->write(json_encode($errorData, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($status);
    }

    /**
     * Method to handle internal server errors.
     *
     * @param string $message
     * @return Response
     */
    protected function internalServerError(string $message): Response
    {
        return $this->respondWithError($message, 500);
    }

    /**
     * A helper to handle successful responses with a message.
     *
     * @param string $message
     * @param int $status
     * @return Response
     */
    protected function respondWithMessage(string $message, int $status = 200): Response
    {
        return $this->respondWithData(['message' => $message], $status);
    }

    /**
     * Helper for responding with validation errors.
     *
     * @param array $errors
     * @return Response
     */
    protected function respondWithValidationError(array $errors): Response
    {
        return $this->respondWithData([
            'error'   => 'Validation failed',
            'details' => $errors,
        ], 422);  // Unprocessable Entity
    }
}
