<?php

namespace gift\app\actions;

use gift\app\services\box\BoxService;
use gift\app\services\Exceptions\BoxServiceBadDataException;
use gift\app\services\exceptions\BoxServiceDataNotFoundException;
use gift\app\services\Exceptions\BoxServiceUpdateFailException;
use gift\app\services\exceptions\ExceptionTokenVerify;
use gift\app\services\prestations\PrestationsService;
use gift\app\services\utils\CsrfService;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class PostPrestationDelete extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $data = $rq->getParsedBody();
        $routeContext = RouteContext::fromRequest($rq);
        $routeParser = $routeContext->getRouteParser();
        $idPresta = $data['id'];
        try {
            CsrfService::check($data['csrf']);
        } catch (ExceptionTokenVerify $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
        $boxService = new BoxService();
        try {
            $boxService->retirerPrestation($idPresta, $_SESSION['box'], $data['quantite']);
        } catch (BoxServiceBadDataException|BoxServiceUpdateFailException|BoxServiceDataNotFoundException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
        return $rs->withHeader('Location', $routeParser->urlFor('boxId',["id"=>$_SESSION['box']]))->withStatus(302);
    }
}