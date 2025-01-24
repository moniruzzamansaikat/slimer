<?php

namespace App\Application\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class JwtMiddleware
{
    private string $jwtSecret; // Replace with your secure secret key

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $this->jwtSecret = env('JWT_SECRET');

        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader) {
            return $this->unauthorizedResponse('Authorization header not provided');
        }

        $token = str_replace('Bearer ', '', $authHeader);

        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));

            $request = $request->withAttribute('user', $decoded->user);
        } catch (\Exception $e) {
            return $this->unauthorizedResponse('Unauthorized access', $e->getMessage());
        }

        return $handler->handle($request);
    }

    /**
     * Generate an unauthorized response with a customizable message.
     *
     * @param string $message
     * @param string|null $details
     * @return Response
     */
    private function unauthorizedResponse(string $message, ?string $details = null): Response
    {
        $response = new \Slim\Psr7\Response();
        $errorData = [
            'error' => $message,
        ];

        if ($details) {
            $errorData['details'] = $details;
        }

        $response->getBody()->write(json_encode($errorData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
}
