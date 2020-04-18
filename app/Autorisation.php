<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Autorisation extends Model
{
    protected $guarded = [];
    
    public function requete() {
        return $this->belongsTo('App\Requete');
    }

    public function demandeur() {
        return $this->belongsTo('App\Demandeur');
    }
}
