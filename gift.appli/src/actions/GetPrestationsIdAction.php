<?php

namespace gift\app\actions;

use gift\app\services\prestations\PrestationNotFoundException;
use gift\app\services\prestations\PrestationsService;
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
        if (!isset($id)) {
            throw new HttpBadRequestException($rq, "La prestation est obligatoire");
        }
        $prestationsService = new PrestationsService();
        try {
            $prestations = $prestationsService->getPrestationsByCategorie($id);
        } catch (PrestationNotFoundException $e) {
            throw new HttpNotFoundException($rq, "La prestation n'existe pas");
        }
        $twig = Twig::fromRequest($rq);
        try {
            return $twig->render($rs, 'prestation/PrestationID.twig', ["prestations"=>$prestations]);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            throw new HttpInternalServerErrorException($rq);
        }
    }
}