<?php


use gift\api\services\utils\DB;
use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true,false,false);
$app->setBasePath('/projet/GiftBox_Project/gift.api/public');
DB::initConnection();
return $app;
