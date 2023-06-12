<?php

namespace gift\app\actions;

use gift\app\services\box\BoxService;
use gift\app\services\utils\CsrfService;
use gift\app\services\utils\ExceptionTokenVerify;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use gift\app\services\prestations\PrestationsService;
use Slim\Routing\RouteContext;

class PostAjoutCategorieAction extends AbstractAction{
    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $post_data = $rq->getParsedBody();
        $routeContext = RouteContext::fromRequest($rq);
        $routeParser = $routeContext->getRouteParser();
        $routeParser->urlFor('boxes');
        $token = $post_data['csrf'];
        try{ CsrfService::check($token);
        } catch (ExceptionTokenVerify $e) {
          throw new HttpInternalServerErrorException($rq);
        };
            $box_data = [
                'libelle' => $post_data['nomBox'],
                'description' => $post_data['descBox'],
                'message_kdo' => $post_data['mCadeau'],
                'kdo' => $post_data['cadeau'],
            ];
$boxService = new BoxService();        
                $boxService->creation($box_data);
            
                return $rs->withStatus(302)->withHeader('Location', $routeParser->urlFor('boxes'));
       
        
        }
}