<?php

use gift\app\actions\GetCategorieIdAction;
use gift\app\actions\GetCategoriesAction;
use gift\app\actions\GetMainAction;
use gift\app\actions\GetNewBoxesAction;
use gift\app\actions\GetPrestationsAction;
use gift\app\actions\GetPrestationsIdAction;
use Slim\App;

return function (App $app) {
    $app->get('/', GetMainAction::class);
    $app->get('/categories',GetCategoriesAction::class);
    $app->get('/categories/{id}',GetCategorieIdAction::class);
    $app->get('/categories/{id:\d+}/prestation', GetPrestationsIdAction::class)->setName('categ2prestas');
    $app->get('/prestation', GetPrestationsAction::class);
    $app->get("/boxes/new",GetNewBoxesAction::class);
    $app->post("/boxes/new", GetNewBoxesAction::class);
};