<?php

namespace gift\api\models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    protected $table = 'categorie';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function prestation(): HasMany
    {
        return $this->hasMany(Prestation::class,"cat_id");
    }

}