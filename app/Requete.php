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

    public function scopeSearch($query,$dmeur,$seltypds,$dt_deb,$dt_fin) {
        if ($seltypds == null && ($dt_deb == null || $dt_fin == null) && $dmeur == null) return $query;

        if (!($seltypds == null)) {
          $query->whereIn('type_demande_id', $seltypds);
        }

        if ( (!($dt_deb == null)) && (!($dt_fin == null)) ) {
          $query->whereBetween('created_at', [Carbon::createFromFormat('d/m/Y', $dt_deb)->format('Y-m-d'),Carbon::createFromFormat('d/m/Y', $dt_fin)->format('Y-m-d')]);
        }

        if (!($dmeur == null)) {
          $query->where('phonenum', $dmeur);
        }

        return $query;
    }
}
