<?php


use gift\api\actions\GetApiBoxesAction;
use gift\api\actions\GetApiBoxesIdAction;
use gift\api\actions\GetApiCategoriesAction;
use gift\api\actions\GetMainAction;
use Slim\App;

return function (App $app) {
    $app->get('/', GetMainAction::class)->setName('home');
    $app->get('/api/categories', GetApiCategoriesAction::class)->setName('categories');
    $app->get('/api/boxes', GetApiBoxesAction::class)->setName('boxes');
    $app->get('/api/boxes/{id}', GetApiBoxesIdAction::class)->setName('boxesId');
};