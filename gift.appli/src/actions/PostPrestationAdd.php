<?php

namespace gift\app\actions;

use gift\app\actions\AbstractAction;
use gift\app\services\box\BoxService;
use gift\app\services\Exceptions\BoxServiceBadDataException;
use gift\app\services\utils\CsrfService;
    use gift\app\services\utils\ExceptionTokenVerify;
    use Slim\Exception\HttpInternalServerErrorException;
    use Slim\Psr7\Request;
    use Slim\Psr7\Response;
    use gift\app\services\prestations\PrestationsService;
    use Slim\Routing\RouteContext;
class PostPrestationAdd extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $post_data = $rq->getParsedBody();
        $routeContext = RouteContext::fromRequest($rq);
        $routeParser = $routeContext->getRouteParser();
        $idPresta = $post_data['id'];

        if (!isset($_SESSION['box'])) {
            return $rs->withStatus(302)->withHeader('Location', $routeParser->urlFor('formulaireBox'));
        }
        $id =  $_SESSION['box'];

        $boxservice = new BoxService();
        try {
            $boxservice->ajoutPrestation($idPresta, $id);
        } catch (BoxServiceBadDataException $e) {
            throw new HttpInternalServerErrorException($rq);
        }

        return $rs->withStatus(302)->withHeader('Location', $routeParser->urlFor('prestations'));
}
}