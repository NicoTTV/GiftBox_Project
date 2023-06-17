<?php

namespace gift\app\actions;

use gift\app\services\exceptions\ExceptionTokenGenerate;
use gift\app\services\utils\CsrfService;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GetInscriptionAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $twig = Twig::fromRequest($rq);
        try {
            $csrf = CsrfService::generate();
        } catch (ExceptionTokenGenerate $e) {
            throw new HttpInternalServerErrorException($rq);
        }
        try {
            return $twig->render($rs, 'user/inscription.twig', ['csrf' => $csrf]);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}