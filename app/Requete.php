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

    public function scopeSearch($query,$seltypds,$dt_deb,$dt_fin) {
      if ($seltypds == null && ($dt_deb == null || $dt_fin == null)) return $query;

      if ($seltypds == null) {
        return $this->scopeSearchByPeriode($query, $dt_deb,$dt_fin);
      } elseif($dt_deb == null || $dt_fin == null) {
        return $this->scopeSearchByTypeDemande($query, $seltypds);
      } else {
        return $this->scopeSearchByAll($query,$seltypds,$dt_deb,$dt_fin);
      }
    }

    public function scopeSearchByTypeDemande($query, $seltypds) {
      if ($seltypds == null) return $query;

      return $query
        ->whereIn('type_demande_id', $seltypds)
        ;
    }

    public function scopeSearchByStatutAutorisation($query, $statauts) {
      if ($seltypds == null) return $query;



      return $query
        ->whereIn('type_demande_id', $seltypds)
        ;
    }

    public function scopeSearchByPeriode($query, $dt_deb,$dt_fin) {
      if ($dt_deb == null || $dt_fin == null) return $query;

      return $query
        ->whereBetween('created_at', [$dt_deb,$dt_fin])
        ;
    }

    public function scopeSearchByAll($query,$seltypds,$dt_deb,$dt_fin) {
      if ($dt_deb == null || $dt_fin == null) return $query;

      return $query
        ->whereIn('type_demande_id', $seltypds)
        ->whereBetween('created_at', [$dt_deb,$dt_fin])
        ;
    }
}
