<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Requete extends Model
{
    protected $guarded = [];

    public function type_demande() {
        return $this->belongsTo('App\TypeDemande');
    }

    public function type_reponse() {
        return $this->belongsTo('App\TypeReponse');
    }

    public function autorisation()
    {
        return $this->hasOne('App\Autorisation');
    }

    public function Finalize($res_code) {

        $this->type_reponse_id = $res_code;
        $this->date_end = Carbon::now();
        $this->duree_traitement_milli = $this->date_start->diffInMilliseconds($this->date_end);
        $this->duree_traitement_micro = $this->date_start->diffInMicroseconds($this->date_end);

        $this->save();

    }

    public function scopeSearch($query, $type_demande_id,$dt_deb,$dt_fin) {
      if ($type_demande_id == null && ($dt_deb == null || $dt_fin == null)) return $query;

      if ($type_demande_id == null) {
        return $this->scopeSearchByPeriode($query, $dt_deb,$dt_fin);
      } else {
        return $this->scopeSearchByTypeDemande($query, $type_demande_id);
      }
    }

    public function scopeSearchByTypeDemande($query, $type_demande_id) {
      if ($type_demande_id == null) return $query;

      return $query
        ->where('type_demande_id', '=', $type_demande_id)
        ;
    }

    public function scopeSearchByPeriode($query, $dt_deb,$dt_fin) {
      if ($dt_deb == null || $dt_fin == null) return $query;

      return $query
        ->whereBetween('created_at', [$dt_deb,$dt_fin])
        ;
    }
}
