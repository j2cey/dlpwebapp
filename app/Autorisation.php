<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Autorisation extends Model
{
    protected $guarded = [];

    public function requete() {
        return $this->belongsTo('App\Requete');
    }

    public function type_demande() {
        return $this->belongsTo('App\TypeDemande');
    }
}
