<?php
declare(strict_types=1);
const ROOT = __DIR__ . "/../src/";

require_once __DIR__ . '/../src/vendor/autoload.php';

/* application boostrap */
$app = (require_once __DIR__ . '/../src/conf/Bootstrap.php');

/* routes loading */
(require_once __DIR__ . '/../src/conf/routes.php')($app);


$app->run();
