<?php

namespace gift\app\actions;

use Carbon\Exceptions\InvalidIntervalException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetMainAction extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $view = Twig::fromRequest($rq);
        $routeContext = RouteContext::fromRequest($rq);
        $routeParser = $routeContext->getRouteParser();
        $routeParser->urlFor('categories');
        $routeParser->urlFor('prestations');
        $routeParser->urlFor('boxes');
        $routeParser->urlFor('home');
        try {
            return $view->render($rs, 'index.twig');
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            throw new HttpInternalServerErrorException($rq);
        }
    }
}