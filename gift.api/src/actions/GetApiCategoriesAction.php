<?php

namespace gift\api\actions;

use gift\api\models\Categorie;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetApiCategoriesAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $categories = Categorie::all();
        $data = [];
        foreach ($categories as $categorie) {
            $data[] = [
                'type' => 'collection',
                'count' => $categories->count(),
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