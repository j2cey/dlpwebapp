<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeReponse extends Model
{
    public function requetes()
    {
        return $this->hasMany('App\Requete');
    }
}
