<?php

namespace gift\app\actions;

use gift\app\services\box\BoxService;
use gift\app\services\prestations\PrestationNotFoundException;
use gift\app\services\prestations\PrestationsService;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class GetNewBoxesAction extends AbstractAction
{

    /**
     * @inheritDoc
     */
    public function __invoke(Request $rq, Response $rs, $args): Response
    {

    }
}