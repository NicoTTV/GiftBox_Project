<?php

use gift\app\actions\GetCategorieIdAction;
use gift\app\actions\GetCategoriesAction;
use gift\app\actions\GetConnexionAction;
use gift\app\actions\GetAjoutCategorieAction;
use gift\app\actions\GetMainAction;
use gift\app\actions\GetNewBoxesAction;
use gift\app\actions\GetPrestationsAction;
use gift\app\actions\GetPrestationsIdAction;
use gift\app\actions\PostAjoutCategorieAction;
use Slim\App;

return function (App $app) {
    $app->get('/', GetMainAction::class)->setName('home');
    $app->get('/categories',GetCategoriesAction::class)->setName('categories');
    $app->get('/categories/{id}',GetCategorieIdAction::class)->setName('categorieId');
    $app->get('/categories/{id:\d+}/prestation', GetPrestationsIdAction::class)->setName('categ2prestas');
    $app->get('/catgories/formulaire', GetAjoutCategorieAction::class)->setName('formulaire');
    $app->post('/categories/formulaire', PostAjoutCategorieAction::class)->setName('catCreate');
    $app->get('/prestation', GetPrestationsAction::class)->setName('prestations');
    $app->get("/boxes/new",GetNewBoxesAction::class)->setName('boxes');
    $app->post("/boxes/new", GetNewBoxesAction::class);
    $app->get("/connexion", GetConnexionAction::class)->setName("connexion");
};