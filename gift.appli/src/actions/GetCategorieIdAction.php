<?php

namespace gift\app\actions;

use gift\app\services\prestations\CategorieNotFoundException;
use gift\app\services\prestations\PrestationNotFoundException;
use gift\app\services\prestations\PrestationsService;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class GetCategorieIdAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $id = intval($args["id"]);
        if (!isset($id)) {
            throw new HttpBadRequestException($rq, "L'id de la categorie est obligatoire");
        }
        $prestationsService = new PrestationsService();
        try {
            $data = $prestationsService->getCategoriesById($id);
        }catch (PrestationNotFoundException|CategorieNotFoundException){
            throw new HttpNotFoundException($rq, "La categorie n'existe pas");
        }
        $twig = Twig::fromRequest($rq);
        return $twig->render($rs, 'categorie/CategorieID.twig', $data);
    }
}