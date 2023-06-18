<?php

namespace gift\app\actions;

use gift\app\services\exceptions\ExceptionTokenGenerate;
use gift\app\services\exceptions\PrestationNotFoundException;
use gift\app\services\prestations\PrestationsService;
use gift\app\services\utils\CsrfService;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetPrestationsIdAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $id = $args['id'];
        $get_data = $rq->getQueryParams();
        if (!isset($id)) {
            throw new HttpBadRequestException($rq, "La prestation est obligatoire");
        }
        $idUser = null;
        if (isset($_SESSION['user'])) {
            $idUser = unserialize($_SESSION['user']['id']);
        }
        try {
            $csrf = CsrfService::generate();
        } catch (ExceptionTokenGenerate $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
        $prestationsService = new PrestationsService();
        try {
            $prestations = $prestationsService->getPrestationsByCategorie($id,2);
           if(isset($get_data['triec'])){
            $prestations = $prestationsService->getPrestationsByCategorie($id,0);
           }
           if(isset($get_data['tried'])){
            $prestations = $prestationsService->getPrestationsByCategorie($id,1);
           }
        } catch (PrestationNotFoundException $e) {
            throw new HttpNotFoundException($rq, "La prestation n'existe pas");
        }
        $twig = Twig::fromRequest($rq);
        try {
            return $twig->render($rs, 'prestation/index.twig', ["prestations"=>$prestations,"id"=>$id, "csrf" => $csrf, "idUser" => $idUser]);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            throw new HttpInternalServerErrorException($rq);
        }
    }
}