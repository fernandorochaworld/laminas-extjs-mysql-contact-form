<?php

namespace Form\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        // do some work
        // die('middleware');
        // return $this->redirect()->toRoute('home');
    }
}
