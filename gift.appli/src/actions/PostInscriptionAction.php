<?php

namespace gift\app\actions;

use gift\app\services\exceptions\BadDataUserException;
use gift\app\services\exceptions\ExceptionTokenVerify;
use gift\app\services\exceptions\UserNotFoundException;
use gift\app\services\exceptions\UserRegisterException;
use gift\app\services\user\UserService;
use gift\app\services\utils\CsrfService;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class PostInscriptionAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $data = $rq->getParsedBody();
        try {
            CsrfService::check($data['csrf']);
        } catch (ExceptionTokenVerify $e) {
            throw new HttpInternalServerErrorException($rq);
        }
        $userService = new UserService();
        try {
            $userService->inscription($data);
        } catch (BadDataUserException|UserNotFoundException|UserRegisterException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
        return $rs->withHeader('Location', '/')->withStatus(302);
    }
}