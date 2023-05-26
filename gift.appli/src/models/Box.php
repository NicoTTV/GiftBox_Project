<?php

namespace gift\app\models;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $table = 'box';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
}