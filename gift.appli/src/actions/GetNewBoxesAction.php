<?php

namespace gift\app\actions;

use gift\app\services\prestations\CategorieNotFoundException;
use gift\app\services\prestations\PrestationsService;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetNewBoxesAction extends AbstractAction
{

    /**
     * @inheritDoc
     * @throws CategorieNotFoundException
     */
    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $prestationsServices = new PrestationsService();
        $categories = $prestationsServices->getCategories();

        $twig = Twig::fromRequest($rq);
        try {
            return $twig->render($rs, 'boxes/index.twig');
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            throw new HttpInternalServerErrorException($rq);
        }

    }
}