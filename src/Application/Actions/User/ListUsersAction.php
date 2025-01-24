<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Capsule\Manager as Capsule;

class ListUsersAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $users = User::all();

        return $this->respondWithData($users);
    }
}
