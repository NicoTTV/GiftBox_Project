<?php

namespace gift\app\actions;

use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetAjoutCategorieAction extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $twig = Twig::fromRequest($rq);
        try {
            return $twig->render($rs, 'categorie/formulaire.twig');
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            throw new HttpInternalServerErrorException($rq);
        }
    }
}