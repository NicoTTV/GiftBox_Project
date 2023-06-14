<?php

namespace gift\api\actions;


use gift\api\models\Box;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetApiBoxesIdAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $boxId = $args['id'];
        $box = Box::find($boxId);

        $prestations = $box->prestations()->get();

        $prestationsData = [];
        foreach ($prestations as $prestation) {
            $prestationsData[] = [
                "prestation" => [
                    "libelle" => $prestation->libelle,
                    "description" => $prestation->description,
                    "contenu" => [
                        "box_id" => $boxId,
                        "presta_id" => $prestation->id,
                        "quantite" => $prestation->pivot->quantite
                    ]
                ],
            ];
        }

        $data = [
            'type' => 'resource',
            "box" => [
                "id" => $box->id,
                "libelle" => $box->libelle,
                "description" => $box->description,
                "message_kdo" => $box->message_kdo,
                "statut" => $box->statut,
                "prestations" => $prestationsData
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}