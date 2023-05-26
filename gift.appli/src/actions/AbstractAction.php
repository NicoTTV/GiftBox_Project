<?php

namespace gift\app\actions;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 *
 */
abstract class AbstractAction
{

    /**
     * @param Request $rq
     * @param Response $rs
     * @param $args
     * @return Response
     */
    abstract public function __invoke(Request $rq, Response $rs, $args):Response;
}