<?php

namespace gift\app\services\box;

use Exception;
use gift\app\models\Box;
use gift\app\models\Prestation;
use gift\app\models\User;
use gift\app\services\Exceptions\BoxServiceBadDataException;
use gift\app\services\Exceptions\BoxUpdateFailException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ramsey\Uuid\Uuid;
use Throwable;

/**
 *
 */
class BoxService
{
    /**
     * @param array $cadeau
     * @return string
     * @throws BoxServiceBadDataException
     * @throws BoxUpdateFailException
     * @throws Throwable
     */
    public function creation(array $cadeau): string
    {
        if (!isset($cadeau['libelle']) && !isset($cadeau['description']))
            throw new BoxServiceBadDataException('Bad data: libelle and description');

        if ($cadeau['libelle'] !== filter_var($cadeau['libelle'],FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BoxServiceBadDataException("Bad data : libelle");

        if ($cadeau['description'] !== filter_var($cadeau['description'],FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BoxServiceBadDataException("Bad data : description");

        if (!isset($cadeau['kdo']) && $cadeau['kdo'] !== 0 && $cadeau['kdo'] !== 1)
            throw new BoxServiceBadDataException("Bad data : kdo");

        if ($cadeau['message_kdo'] !== filter_var($cadeau['message_kdo'],FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BoxServiceBadDataException("Bad data : message_kdo");

        if ($cadeau['url'] !== filter_var($cadeau['url'],FILTER_SANITIZE_URL) && !filter_var($cadeau['url'],FILTER_VALIDATE_URL))
            throw new BoxServiceBadDataException("Bad data : url");

        try {
            $newBox = new Box();
            $newBox->libelle = $cadeau['libelle'];
            $newBox->description = $cadeau['description'];
            $newBox->kdo = $cadeau['kdo'];
            $newBox->message_kdo = $cadeau['message_kdo'];
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

            $_SESSION['box'] = $newBox->id;
        } catch (ModelNotFoundException) {
            throw new BoxUpdateFailException();
        }
        return $url;
    }

    public function affichage():array
    {
        return Box::all()->toArray();
    }

    /**
     * @throws BoxServiceBadDataException
     */
    public function ajoutPrestation(string $id_presta, string $id_coffret): void
    {
        if ($id_presta !== filter_var($id_presta,FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BoxServiceBadDataException("Bad data : id_presta");

        if ($id_coffret !== filter_var($id_coffret,FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BoxServiceBadDataException("Bad data : id_coffret");

        $box = Box::findOrFail($id_coffret);
        if ($box->prestation()->find($id_presta) == null) {
            $box->prestation()->attach($id_presta , ['quantite' => 1]);
        } else {
            $box->prestation()->updateExistingPivot($id_presta, ['quantite' => $box->prestation()->find($id_presta)->pivot->quantite + 1]);
        }
        $prestation = Prestation::findOrFail($id_presta);
        $box->update(['montant' => $box->montant + $prestation->tarif]);
    }

    public function retraitPrestations()
    {

    }

    public function getPrestationByBoxId(string $id)
    {
        return Box::findOrFail($id)->prestation()->get()->toArray();
    }

    public function affichageBoxesUtilisateurs(int $id_user):array
    {
        return User::find($id_user)->usersBoxes()->toArray();
    }
}