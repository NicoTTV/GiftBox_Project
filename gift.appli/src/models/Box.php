<?php

namespace gift\app\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Box extends Model
{
    const CREATED = 1;
    public $incrementing = false;
    protected $table = 'box';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    public function ajouterPrestation($idPrestation)
    {
        $prestations = $this->prestation();
        foreach ($prestations as $prestation) {
            $this->montant += $prestation->tarif;
            if ($prestation->id !== $idPrestation) {
                $prestation = Prestation::findOrFail($idPrestation);
                $this->prestation()->attach($idPrestation);
            }
            $prestation->pivot->quantite++;
            $this->saveOrFail();
        }
    }

    public function prestation(): BelongsToMany
    {
        return $this->belongsToMany(Prestation::class, 'box_prestation', 'box_id', 'presta_id')->withPivot('quantite');
    }
}