<?php

namespace gift\app\actions;

use gift\app\models\Prestation;
use gift\app\services\exceptions\ExceptionTokenGenerate;
use gift\app\services\exceptions\PrestationNotFoundException;
use gift\app\services\prestations\PrestationsService;
use gift\app\services\utils\CsrfService;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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
        try {
            $csrf = CsrfService::generate();
        } catch (ExceptionTokenGenerate $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
        $twig = Twig::fromRequest($rq);
        try {
            return $twig->render($rs, 'prestation/index.twig', ["prestations" => $prestations, "csrf" => $csrf]);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}