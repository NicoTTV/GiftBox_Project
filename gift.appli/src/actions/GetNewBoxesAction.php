<?php

namespace gift\app\actions;

use gift\app\services\utils\CsrfService;
use gift\app\services\utils\ExceptionTokenGenerate;
use Slim\Exception\HttpInternalServerErrorException;
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
        $routeParser->urlFor('boxCreate');
        try {
            $csrf = CsrfService::generate();
        } catch (ExceptionTokenGenerate $e) {
            throw new HttpInternalServerErrorException($rq);
        }
        $twig = Twig::fromRequest($rq);
        try {
            return $twig->render($rs, 'boxes/creationCoffret.twig', ['csrf' => $csrf]);
        } catch (LoaderError|RuntimeError|SyntaxError) {
            throw new HttpInternalServerErrorException($rq);
        }
    }
}