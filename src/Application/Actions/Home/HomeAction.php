<?php

declare(strict_types=1);

namespace App\Application\Actions\Home;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class HomeAction extends Action {
    protected function action(): Response
    {
        $data = [
            'site_title'         => 'My amazing site',
            'site_currency'      => '$',
            'site_currency_text' => 'USD'
        ];
        
        return $this->respondWithData($data);
    }
}
