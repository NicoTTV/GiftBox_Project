<?php

namespace gift\app\actions;

use gift\app\services\box\BoxService;
use gift\app\services\Exceptions\BoxUpdateFailException;
use gift\app\services\Exceptions\BoxServiceBadDataException;
use gift\app\services\exceptions\ExceptionTokenVerify;
use gift\app\services\utils\CsrfService;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class PostNewBoxesAction extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $post_data = $rq->getParsedBody();
        $routeContext = RouteContext::fromRequest($rq);
        $routeParser = $routeContext->getRouteParser();
        $token = $post_data['csrf'];
        try {
            CsrfService::check($token);
        } catch (ExceptionTokenVerify $e) {
            throw new HttpInternalServerErrorException($rq);
        }
        $box_data = [
            'libelle' => $post_data['nomBox'],
            'description' => $post_data['descBox'],
            'message_kdo' => $post_data['mCadeau'],
            'kdo' => $post_data['cadeau'] ?? 0,
            'url' => $routeParser->urlFor('boxes')
        ];

        $boxService = new BoxService();
        try {
            $url = $boxService->creation($box_data);
        } catch (BoxServiceBadDataException|BoxUpdateFailException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        } catch (\Throwable $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
        return $rs->withStatus(302)->withHeader('Location', $routeParser->urlFor('boxes'));
    }
}