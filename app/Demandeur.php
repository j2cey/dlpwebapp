<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Demandeur extends Model
{
    protected $guarded = [];

    public function requetes()
    {
        return $this->hasMany('App\Requete');
    }

    public function autorisations()
    {
        return $this->hasMany('App\Autorisation');
    }

    public function autorisationEnCours()
    {
        return $this->autorisations()->where('is_active', 1)->get();
    }

    public function aAutorisationEnCours() {
        return $this->autorisations()->where('is_active', 1)->count() == 0 ? false : true;
    }

    public function consultation() {
      $autorisation_en_cours = $this->autorisationEnCours()->first();
      //dd($autorisation_en_cours);
      if (is_null($autorisation_en_cours)) {
        return "aucune autorisation eb cours";
      } else {
        return $autorisation_en_cours->msg;
      }
    }
}
