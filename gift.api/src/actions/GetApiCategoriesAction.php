<?php

namespace gift\api\actions;

use gift\api\models\Categorie;
use gift\api\services\prestations\CategorieNotFoundException;
use gift\api\services\prestations\PrestationsService;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetApiCategoriesAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $categories = new PrestationsService();
        try {
            $categories = $categories->getCategories();
        } catch (CategorieNotFoundException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
        $data = [];
        foreach ($categories as $categorie) {
            $data[] = [
                'type' => 'collection',
                'count' => count($categories),
                'categories' => [
                    "categorie" => [
                        "id" => $categorie->id,
                        "libelle" => $categorie->libelle,
                        "description" => $categorie->description,
                    ],
                    "links" => [
                        "self" => [
                            "href" => "/categories/" . $categorie->id
                        ]
                    ]
                ]
            ];
        }

        $rs->getBody()->write(json_encode($data));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}