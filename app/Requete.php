<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requete extends Model
{
    protected $guarded = [];

    public function demandeur() {
        return $this->belongsTo('App\Demandeur');
    }

    public function autorisation()
    {
        return $this->hasOne('App\Autorisation');
    }
}
