<?php


use gift\api\services\utils\DB;
use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true,false,false);
DB::initConnection();
return $app;
