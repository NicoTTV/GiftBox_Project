<?php

namespace gift\app\actions;

use gift\app\actions\AbstractAction;
use gift\app\services\box\BoxService;
use gift\app\services\Exceptions\BoxServiceBadDataException;
use gift\app\services\exceptions\BoxServiceDataNotFoundException;
use gift\app\services\Exceptions\BoxServiceUpdateFailException;
use gift\app\services\exceptions\ExceptionTokenVerify;
use gift\app\services\utils\CsrfService;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class PostPrestationAdd extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $post_data = $rq->getParsedBody();
        $routeContext = RouteContext::fromRequest($rq);
        $routeParser = $routeContext->getRouteParser();
        $idPresta = $post_data['id'];
        try {
            CsrfService::check($post_data['csrf']);
        } catch (ExceptionTokenVerify $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }

        if (!isset($_SESSION['box'])) {
            return $rs->withStatus(302)->withHeader('Location', $routeParser->urlFor('formulaireBox'));
        }
        $id = $_SESSION['box'];

        $boxservice = new BoxService();
        try {
            $boxservice->ajoutPrestation($idPresta, $id);
        } catch (BoxServiceUpdateFailException|BoxServiceDataNotFoundException|BoxServiceBadDataException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }

        return $rs->withStatus(302)->withHeader('Location', $routeParser->urlFor('prestations'));
    }
}