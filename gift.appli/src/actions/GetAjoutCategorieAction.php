<?php

namespace gift\app\actions;

use gift\app\services\exceptions\ExceptionTokenGenerate;
use gift\app\services\utils\CsrfService;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetAjoutCategorieAction extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        try {
            $csrfInput = CsrfService::generate();
        } catch (ExceptionTokenGenerate $exception) {
            throw new HttpInternalServerErrorException($rq);
        }
        $twig = Twig::fromRequest($rq);
        try {
            return $twig->render($rs, 'categorie/formulaire.twig', ["csrf" => $csrfInput]);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            throw new HttpInternalServerErrorException($rq);
        }
    }
}