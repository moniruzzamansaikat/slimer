<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Models\User;
use Psr\Http\Message\ResponseInterface as Response;

class ViewUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');

        $user = User::find($userId);
        
        return $this->respondWithData($user);
    }
}
