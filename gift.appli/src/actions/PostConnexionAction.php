<?php

namespace gift\app\actions;

use gift\app\services\exceptions\BadDataUserException;
use gift\app\services\exceptions\UserNotFoundException;
use gift\app\services\user\UserService;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class PostConnexionAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $data = $rq->getParsedBody();
        $userService = new UserService();
        try {
            $userService->connexion($data);
        } catch (BadDataUserException|UserNotFoundException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
        return $rs->withHeader('Location', '/')->withStatus(302);
    }
}