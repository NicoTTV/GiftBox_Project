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
        $prestationsService = new PrestationsService();
        try {
            $categories = $prestationsService->getCategories();
        } catch (CategorieNotFoundException $e) {
            throw new HttpNotFoundException($rq, "Il n'y à pas de catégories");
        }
        $restauration = $categories[0];
        $hebergement = $categories[1];
        $attention = $categories[2];
        $activite = $categories[3];
        $data = ["restauration" => $restauration,
            "hebergement" => $hebergement,
            "attention" =>  $attention,
            "activite" =>  $activite];
        $twig = Twig::fromRequest($rq);
        try {
            return $twig->render($rs, 'categorie/index.twig', $data);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            throw new HttpInternalServerErrorException($rq);
        }
    }
}