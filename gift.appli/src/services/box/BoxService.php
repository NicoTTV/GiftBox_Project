<?php

namespace gift\app\services\box;

use Exception;
use gift\app\models\Box;
use gift\app\services\Exceptions\BoxServiceBadDataException;
use gift\app\services\prestations\PrestationsServiceBadDataException;
use Ramsey\Uuid\Uuid;
use Throwable;

/**
 *
 */
class BoxService
{
    /**
     * @throws BoxServiceBadDataException
     * @throws BoxUpdateFailException
     */
    public function creation(array $cadeau): string
    {
        if (!isset($cadeau['libelle']) && !isset($cadeau['description']))
            throw new BoxServiceBadDataException('Bad data: libelle and description');

        if ($cadeau['libelle'] !== filter_var($cadeau['libelle'],FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BoxServiceBadDataException("Bad data : libelle");

        if ($cadeau['description'] !== filter_var($cadeau['description'],FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BoxServiceBadDataException("Bad data : description");

        if ($cadeau['kdo'] !== filter_var($cadeau['kdo'],FILTER_SANITIZE_NUMBER_INT))
            throw new BoxServiceBadDataException("Bad data : kdo");

        if ($cadeau['message_kdo'] !== filter_var($cadeau['message_kdo'],FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BoxServiceBadDataException("Bad data : message_kdo");

        if ($cadeau['url'] !== filter_var($cadeau['url'],FILTER_SANITIZE_URL) && !filter_var($cadeau['url'],FILTER_VALIDATE_URL))
            throw new BoxServiceBadDataException("Bad data : url");

        try {
            $newBox = new Box($cadeau);
            $newBox->montant = 0;
            try {
                $newBox->token = bin2hex(random_bytes(64));
                $url = $cadeau['url'].'/'.$newBox->token;
            } catch (Exception) {
                throw new BoxUpdateFailException('Token error');
            }
            $newBox->statut = Box::CREATED;
            $newBox->id = Uuid::uuid4()->toString();
            $newBox->saveOrFail();
        } catch (Throwable) {
            throw new BoxUpdateFailException();
        }
        return $url;
    }

    public function affichage():array
    {
        return Box::all()->toArray();
    }

    public function ajoutPrestations(int $id_presta,int $id_coffret)
    {
        Box::findOrFail($id_coffret);
    }

    public function retraitPrestations()
    {

    }
}