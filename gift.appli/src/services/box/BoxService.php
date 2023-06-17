<?php

namespace gift\app\services\box;

use Exception;
use gift\app\models\Box;
use gift\app\models\Prestation;
use gift\app\services\exceptions\BoxServiceBadDataException;
use gift\app\services\exceptions\BoxServiceDataNotFoundException;
use gift\app\services\exceptions\BoxServiceUpdateFailException;
use gift\app\services\exceptions\PrestationNotFoundException;
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
     * @throws BoxServiceUpdateFailException
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
                throw new BoxServiceUpdateFailException('Token error');
            }
            $newBox->id_user = unserialize($_SESSION['user'])[0]['id'];
            $newBox->estPredefinis = 0;
            $newBox->statut = Box::CREATED;
            $newBox->id = Uuid::uuid4()->toString();
            $newBox->saveOrFail();

            $_SESSION['box'] = $newBox->id;
        } catch (ModelNotFoundException) {
            throw new BoxServiceUpdateFailException();
        }
        return $url;
    }

    /**
     * @throws BoxServiceDataNotFoundException
     */
    public function affichageBoxesPredefinis():array
    {
        try {
            return Box::where('estPredefinis',1)->get()->toArray();
        }catch (ModelNotFoundException) {
            throw new BoxServiceDataNotFoundException();
        }
    }


    /**
     * @throws BoxServiceBadDataException
     * @throws BoxServiceDataNotFoundException
     */
    private function verifyData(string $id_presta, string $id_coffret): Box
    {
        if ($id_presta !== filter_var($id_presta,FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BoxServiceBadDataException("Bad data : id_presta");

        if ($id_coffret !== filter_var($id_coffret,FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new BoxServiceBadDataException("Bad data : id_coffret");

        try {
            return Box::findOrFail($id_coffret);
        }catch (ModelNotFoundException) {
            throw new BoxServiceDataNotFoundException("Bad data : id_coffret");
        }
    }

    /**
     * @throws BoxServiceBadDataException
     * @throws BoxServiceDataNotFoundException
     * @throws BoxServiceUpdateFailException
     */
    public function ajoutPrestation(string $id_presta, string $id_coffret): void
    {
        $box = $this->verifyData($id_presta, $id_coffret);
        if ($box->prestation()->find($id_presta) == null) {
            $box->prestation()->attach($id_presta , ['quantite' => 1]);
        } else {
            $box->prestation()->updateExistingPivot($id_presta, ['quantite' => $box->prestation()->find($id_presta)->pivot->quantite + 1]);
        }
        try {
            $prestation = Prestation::findOrFail($id_presta);
        }catch (ModelNotFoundException) {
            throw new BoxServiceDataNotFoundException("Bad data : id_presta");
        }
        try {
            $box->update(['montant' => $box->montant + $prestation->tarif]);
        }catch (ModelNotFoundException) {
            throw new BoxServiceUpdateFailException();
        }
    }

    /**
     * @throws BoxServiceBadDataException
     * @throws BoxServiceDataNotFoundException
     * @throws BoxServiceUpdateFailException
     */
    public function retirerPrestation(string $id_presta, string $id_coffret, int $quantite): void
    {

        $box = $this->verifyData($id_presta, $id_coffret);
        if (!filter_var($quantite,FILTER_VALIDATE_INT))
            throw new BoxServiceBadDataException("Bad data : quantite");

        try {
            if ($box->prestation()->find($id_presta) != null) {
                if ($box->prestation()->find($id_presta)->pivot->quantite > 1) {
                    $box->prestation()->updateExistingPivot($id_presta, ['quantite' => $box->prestation()->find($id_presta)->pivot->quantite - 1]);
                } else {
                    $box->prestation()->detach($id_presta);
                }
                $prestation = Prestation::findOrFail($id_presta);
                try {
                    $box->update(['montant' => $box->montant - $prestation->tarif]);
                }catch (ModelNotFoundException) {
                    throw new BoxServiceUpdateFailException();
                }
            }
        }catch (ModelNotFoundException) {
            throw new BoxServiceDataNotFoundException("Bad data : id_presta");
        }
    }

    /**
     * @throws PrestationNotFoundException
     */
    public function getPrestationByBoxId(string $id)
    {
        try {
            return Box::findOrFail($id)->prestation()->get()->toArray();
        }catch (ModelNotFoundException) {
            throw new PrestationNotFoundException();
        }
    }

    /**
     * @throws PrestationNotFoundException
     */
    public function getBoxById(string $id): array
    {
        try {
            return Box::findOrFail($id)->toArray();
        }catch (ModelNotFoundException) {
            throw new PrestationNotFoundException();
        }
    }
}