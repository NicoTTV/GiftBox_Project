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
    public function creation(array $cadeau): void
    {
        if (isset($cadeau['libelle']) && isset($cadeau['description']))
            throw new BoxServiceBadDataException('Bad data: libelle and description');

        if ($cadeau['libelle'] !== filter_var($cadeau['libelle'],FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BoxServiceBadDataException("Bad data : libelle");

        if ($cadeau['description'] !== filter_var($cadeau['description'],FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BoxServiceBadDataException("Bad data : description");

        if ($cadeau['kdo+message_kdo'] !== filter_var($cadeau['kdo+message_kdo'],FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BoxServiceBadDataException("Bad data : libelle");

        if ($cadeau['url'] !== filter_var($cadeau['url'],FILTER_SANITIZE_URL) && !filter_var($cadeau['url'],FILTER_VALIDATE_URL))
            throw new BoxServiceBadDataException("Bad data : description");

        try {
            $newBox = new Box($cadeau);
            $newBox->montant = 0;
            try {
                $newBox->token = bin2hex(random_bytes(64));
            } catch (Exception) {
                throw new BoxUpdateFailException('Token error');
            }
            $newBox->statut = Box::CREATED;
            $newBox->id = Uuid::uuid4()->toString();
            $newBox->saveOrFail();
        } catch (Throwable) {
            throw new BoxUpdateFailException();
        }
    }

    public function affichage()
    {

    }

    public function ajoutPrestations(int $id_presta,int $id_coffret)
    {
        Box::findOrFail($id_coffret);
    }

    public function retraitPrestations()
    {

    }
}