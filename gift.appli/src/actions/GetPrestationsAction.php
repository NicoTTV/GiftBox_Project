<?php

namespace gift\app\actions;

use gift\app\models\Prestation;
use gift\app\services\prestations\CategorieNotFoundException;
use gift\app\services\prestations\PrestationNotFoundException;
use gift\app\services\prestations\PrestationsService;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class GetPrestationsAction extends AbstractAction
{

    /**
     * @inheritDoc
     */
    public function __invoke(Request $rq, Response $rs, $args): Response
    {

        $prestationsService = new PrestationsService();
        try {
            $prestations = $prestationsService->getPrestations();
        } catch (PrestationNotFoundException) {
            throw new HttpNotFoundException($rq, "La prestation n'existe pas");
        }
        $routeContext = RouteContext::fromRequest($rq);
        $routeParser = $routeContext->getRouteParser();
        $routeParser->urlFor('categ2prestas',['id'=>0]);
        $twig = Twig::fromRequest($rq);
        return $twig->render($rs,'prestation/index.twig',["prestations"=>$prestations]);
    }
}