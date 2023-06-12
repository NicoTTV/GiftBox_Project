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
        
    }

    public function prestation(): BelongsToMany
    {
        return $this->belongsToMany(Prestation::class, 'box2presta', 'box_id', 'presta_id')->withPivot('quantite');
    }
}