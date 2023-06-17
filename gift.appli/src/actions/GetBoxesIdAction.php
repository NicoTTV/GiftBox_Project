<?php
namespace gift\app\actions;

use gift\app\actions\AbstractAction;
use gift\app\services\box\BoxService;
use gift\app\services\exceptions\ExceptionTokenGenerate;
use gift\app\services\prestations\CategorieNotFoundException;
use gift\app\services\prestations\PrestationNotFoundException;
use gift\app\services\prestations\PrestationsService;
use gift\app\services\utils\CsrfService;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetBoxesIdAction extends AbstractAction{
    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $id = ($args["id"]);
        if (!isset($id)) {
            throw new HttpBadRequestException($rq, "L'id de la box est obligatoire");
        }
        try {
            $csrf = CsrfService::generate();
        } catch (ExceptionTokenGenerate $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
        $boxService = new BoxService();
        try {
            $data = $boxService->getPrestationByBoxId($id);
        } catch (PrestationNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }
        try {
            $box = $boxService->getBoxById($id);
        } catch (PrestationNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }
        $twig = Twig::fromRequest($rq);
        try {
            return $twig->render($rs, 'boxes/listePrestaBox.twig', ["data"=>$data,"box"=>$box,"csrf"=>$csrf]);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            throw new HttpInternalServerErrorException($rq);
        }
    }
}