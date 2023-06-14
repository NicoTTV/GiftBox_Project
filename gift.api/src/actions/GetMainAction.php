<?php

namespace gift\api\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetMainAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $data = [
            'type' => 'collection',
            'links' => [
                'self' => [
                    'href' => '/'
                ],
                'boxes' => [
                    'href' => '/api/boxes/'
                ],
                'categories' => [
                    'href' => '/api/categories'
                ]
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}