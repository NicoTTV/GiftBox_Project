<?php

use gift\app\actions\GetBoxesAction;
use gift\app\actions\GetCategorieIdAction;
use gift\app\actions\GetCategoriesAction;
use gift\app\actions\GetConnexionAction;
use gift\app\actions\GetAjoutCategorieAction;
use gift\app\actions\GetMainAction;
use gift\app\actions\GetNewBoxesAction;
use gift\app\actions\GetPrestationsAction;
use gift\app\actions\GetPrestationsDetailsAction;
use gift\app\actions\GetPrestationsIdAction;
use gift\app\actions\PostAjoutCategorieAction;
use gift\app\actions\PostNewBoxesAction;
use gift\app\actions\PostPrestationAdd;
use Slim\App;

return function (App $app) {
    $app->get('/', GetMainAction::class)->setName('home');
    $app->get('/categories',GetCategoriesAction::class)->setName('categories');
    $app->get('/categories/{id}',GetCategorieIdAction::class)->setName('categorieId');
    $app->get('/categories/{id:\d+}/prestation', GetPrestationsIdAction::class)->setName('categ2prestas');
    $app->get('/catgories/formulaire', GetAjoutCategorieAction::class)->setName('formulaireCateg');
    $app->post('/categories/formulaire', PostAjoutCategorieAction::class)->setName('catCreate');
    $app->get('/prestation', GetPrestationsAction::class)->setName('prestations');
    $app->get('/prestation/{id}', GetPrestationsDetailsAction::class)->setName('prestaDetails');
    $app->post('/box/prestation/add', PostPrestationAdd::class)->setName('PrestaAdd');
    $app->get("/boxes/new",GetNewBoxesAction::class)->setName('formulaireBox');
    $app->get("/boxes",GetBoxesAction::class)->setName('boxes');
    $app->post("/boxes/new", PostNewBoxesAction::class)->setName('boxCreate');
    $app->get("/connexion", GetConnexionAction::class)->setName("connexion");
};