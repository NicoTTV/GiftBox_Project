<?php

namespace gift\app\actions;

use gift\app\services\box\BoxService;
use gift\app\services\prestations\PrestationNotFoundException;
use gift\app\services\prestations\PrestationsService;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetNewBoxesAction extends AbstractAction
{

    /**
     * @inheritDoc
     */
    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $routeContext = RouteContext::fromRequest($rq);
        $routeParser = $routeContext->getRouteParser();
        $routeParser->urlFor('boxes');
        $twig = Twig::fromRequest($rq);
        try {
            return $twig->render($rs, 'boxes/creationCoffret.twig');
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            throw new HttpInternalServerErrorException($rq);
        }
    }
}