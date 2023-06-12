<?php

namespace gift\app\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetNewBoxesAction extends AbstractAction
{

    /**
     * @inheritDoc
     */
    public function __invoke(Request $rq, Response $rs, $args): Response
    {
        $inputPostText = "";
        $requestMethode = $rq->getMethod();
        if (strtolower($requestMethode) === "post") {
            $inputPostText = "{$rq->getParsedBody()['something']}";
        }
        $html = <<<END
                <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <title>Gift</title>
                </head>
                <body>
                <form action="" method="post">
                    <label for="input">Ecrivez quelque chose</label>
                    <input id="input" type="text" name="something">
                    <p>Le texte est {$inputPostText}</p>
                </form>
                </body>
                </html>
                END;
        $rs->getBody()->write($html);
        return $rs;
    }
}