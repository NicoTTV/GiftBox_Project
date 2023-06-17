<?php

namespace gift\app\actions;

use gift\app\services\box\BoxService;
use gift\app\services\exceptions\UserNotFoundException;
use gift\app\services\user\UserService;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetBoxesAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $view = Twig::fromRequest($rq);
        $boxService = new BoxService();
        $boxes = $boxService->affichageBoxesPredefinis();
        $idUser = unserialize($_SESSION['user'])[0]['id'];
        $userService = new UserService();
        try {
            $boxesUser = $userService->affichageBoxesUtilisateurs($idUser);
        } catch (UserNotFoundException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
        try {
            return $view->render($rs, 'boxes/index.twig', ["boxes" => $boxes ,"boxesUser" => $boxesUser]);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            throw new HttpInternalServerErrorException($rq);
        }
    }
}