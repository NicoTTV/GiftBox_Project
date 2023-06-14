<?php

namespace gift\api\actions;

use gift\api\services\prestations\PrestationNotFoundException;
use gift\api\services\prestations\PrestationsService;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class GetApiPrestationsAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $prestationService = new PrestationsService();
        try {
            $prestations = $prestationService->getPrestations();
        } catch (PrestationNotFoundException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }

        $prestationsFormated = [];

        foreach ($prestations as $prestation) {
            $prestationsFormated[] = [
                    "prestation" => [
                        "id" => $prestation['id'],
                        "libelle" => $prestation['libelle'],
                        "description" => $prestation['description'],
                        "tarif" => $prestation['tarif'],
                        "categorie_id" => $prestation['cat_id'],
                    ],
                    "links" => [
                        "self" => [
                            "href" => "/prestations/" . $prestation['id']
                        ]
                    ]
            ];
        }
        $data = [
            'type' => 'collection',
            'count' => count($prestations),
            'prestations' => $prestationsFormated
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}