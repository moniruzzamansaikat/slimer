<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Application\Models\User;
use App\Application\Utils\Validator;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;
use PDO;

class CreateUserAction extends Action
{
    private PDO $db;
    private Validator $validator;

    public function __construct(PDO $db, Validator $validator)
    {
        $this->db        = $db;
        $this->validator = $validator;
    }

    protected function action(): Response
    {
        $request = $this->request;
        $data    = $request->getParsedBody();

        $rules = [
            'first_name' => v::notEmpty()->length(3, 50)->setName('First name'),
            'last_name'  => v::notEmpty()->length(3, 50)->setName('Last name'),
            'email'      => v::notEmpty()->email()->setName('Email'),
            'password'   => v::notEmpty()->length(8, null)->setName('Password'),
        ];

        if (!$this->validator->validate($data, $rules)) {
            return $this->respondWithData([
                'error'   => 'Validation failed',
                'errors' => $this->validator->getErrors(),
            ], 400);
        }

        $firstName = $data['first_name'] ?? null;
        $lastName  = $data['last_name'] ?? null;
        $email     = $data['email'] ?? null;
        $password  = $data['password'] ?? null;

        try {
            $user             = new User();
            $user->first_name = $firstName;
            $user->last_name  = $lastName;
            $user->email      = $email;
            $user->password   = $password;
            $user->save();

            return $this->respondWithData(['message' => 'User created successfully'], 201);
        } catch (\Exception $e) {
            return $this->respondWithData([
                'error'   => 'Validation failed',
                'details' => $e->getMessage(),
            ], 400);
        }
    }
}
