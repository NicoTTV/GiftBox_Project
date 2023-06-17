<?php

namespace gift\app\actions;

use gift\app\services\exceptions\ExceptionTokenVerify;
use gift\app\services\exceptions\PrestationUpdateFailException;
use gift\app\services\prestations\PrestationsServiceBadDataException;
use gift\app\services\utils\CsrfService;
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
        $routeParser->urlFor('categories');
        $token = $post_data['csrf'];
        try{
            CsrfService::check($token);
        } catch (ExceptionTokenVerify $e) {
          throw new HttpInternalServerErrorException($rq);
        };
            $categ_data = [
                'libelle' => $post_data['nomCategorie'],
                'description' => $post_data['descCategorie'],
            ];
            $prestationsService = new PrestationsService();

        try {
            $prestationsService->createCategorie($categ_data);
        } catch (PrestationUpdateFailException|PrestationsServiceBadDataException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }

        return $rs->withStatus(302)->withHeader('Location', $routeParser->urlFor('categories'));
       
        
        }
}