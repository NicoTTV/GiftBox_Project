<?php

namespace gift\api\actions;

use gift\api\models\Box;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetApiBoxesAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $boxes = Box::all();
        $formatedBoxes = [];

        foreach ($boxes as $box) {
            $formatedBoxes[] = [
                    "box" => [
                        "id" => $box->id,
                        "libelle" => $box->libelle,
                    ],
                    "links" => [
                        "self" => [
                            "href" => "/boxes/" . $box->id
                        ]
                    ]
            ];
        }

        $data = [
            'type' => 'collection',
            'count' => $boxes->count(),
            'boxes' => $formatedBoxes
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}