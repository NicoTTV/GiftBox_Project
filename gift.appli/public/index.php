<?php
declare(strict_types=1);
const ROOT = __DIR__ . "/../src/";
session_start();
require_once ROOT . 'vendor/autoload.php';

/* application boostrap */
$app = (require_once ROOT . 'conf/Bootstrap.php');

/* routes loading */
(require_once ROOT . 'conf/routes.php')($app);


$app->run();
