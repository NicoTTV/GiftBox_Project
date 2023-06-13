<?php

namespace gift\app\actions;

use gift\app\models\Categorie;
use gift\app\services\prestations\CategorieNotFoundException;
use gift\app\services\prestations\PrestationsService;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 *
 */
class GetCategoriesAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $routeContext = RouteContext::fromRequest($rq);
        $routeParser = $routeContext->getRouteParser();
        $routeParser->urlFor('formulaireCateg');

        $prestationsService = new PrestationsService();
        try {
            $categories = $prestationsService->getCategories();
        } catch (CategorieNotFoundException $e) {
            throw new HttpNotFoundException($rq, "Il n'y a pas de catégories");
        }
        $routeParser->urlFor('categorieId', ['id' => 0]);
        $data = [
            "categories" => $categories
        ];
        $twig = Twig::fromRequest($rq);
        try {
            return $twig->render($rs, 'categorie/index.twig', $data);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            throw new HttpInternalServerErrorException($rq);
        }
    }
}