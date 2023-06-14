<?php

namespace gift\app\services\prestations;

use gift\app\models\Categorie;
use gift\app\models\Prestation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Throwable;
use gift\app\services\exceptions\PrestationUpdateFailException;

class PrestationsService
{
    /**
     * @throws CategorieNotFoundException
     */
    public function getCategories():array
    {
        try {
            return Categorie::get()->toArray();
        } catch (ModelNotFoundException $e) {
            throw new CategorieNotFoundException();
        }
    }

    /**
     * @throws CategorieNotFoundException
     */
    public function getCategoriesById(int $id):array
    {
        try {
            return Categorie::findOrFail($id)->toArray();
        } catch (ModelNotFoundException $e) {
            throw new CategorieNotFoundException();
        }
    }

    /**
     * @throws PrestationNotFoundException
     */
    public function getPrestationById(string $id):array
    {
        try {
            return Prestation::findOrFail($id)->toArray();
        } catch (ModelNotFoundException $e) {
            throw new PrestationNotFoundException();
        }
    }

    /**
     * @throws PrestationNotFoundException
     */
    public function getPrestationsByCategorie(int $categ_id, int $order = 2)
    {
        try {
            if ($order == 0) {
                return Categorie::findOrFail($categ_id)->prestation()->orderBy('tarif')->get()->toArray();
            } elseif ($order == 1) {
                return Categorie::findOrFail($categ_id)->prestation()->orderBy('tarif','DESC')->get()->toArray();
            }
            return Categorie::findOrFail($categ_id)->prestation()->get()->toArray();
        } catch (ModelNotFoundException $e) {
            throw new PrestationNotFoundException();
        }
    }

    /**
     * @throws PrestationNotFoundException
     */
    public function getPrestations():array
    {
        try {
            return Prestation::get()->toArray();
        } catch (ModelNotFoundException) {
            throw new PrestationNotFoundException();
        }
    }

    /**
     * @throws PrestationUpdateFailException
     */
    public function modifyPrestation(array $attributes)
    {
        try {
            Prestation::findOrFail($attributes['id'])->update($attributes);
        } catch (ModelNotFoundException) {
            throw new PrestationUpdateFailException();
        }
    }

    /**
     * @throws PrestationNotFoundException
     */
    public function defineOrModifyCategPrestation(string $idPresta, int $idCateg)
    {
        try {
            Prestation::findOrFail($idPresta)->associate(Categorie::findOrFail($idCateg));
        } catch (ModelNotFoundException) {
            throw new PrestationNotFoundException();
        }
    }

    /**
     * @throws PrestationUpdateFailException
     * @throws PrestationsServiceBadDataException
     */
    public function createCategorie(array $categ):int
    {
        if ($categ['libelle'] !== filter_var($categ['libelle'],FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new PrestationsServiceBadDataException("Bad data : libelle");

        if ($categ['description'] !== filter_var($categ['description'],FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new PrestationsServiceBadDataException("Bad data : description");

        try {
            $categorie = new Categorie();
            $categorie->libelle = $categ['libelle'];
            $categorie->description = $categ['description'];
            $categorie->saveOrFail();
            return $categorie->id;
        } catch (Throwable) {
            throw new PrestationUpdateFailException();
        }
    }

    /**
     * @throws PrestationUpdateFailException
     */
    public function deleteCategorie(int $id):void
    {
        try {
            Categorie::findOrFail($id)->delete();
        } catch (ModelNotFoundException) {
            throw new PrestationUpdateFailException();
        }
    }
}