<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Requete extends Model
{
    protected $guarded = [];

    public function autorisation()
    {
        return $this->hasOne('App\Autorisation');
    }

    public function Finalize($res_code) {

        $this->resp_code = $res_code;
        $this->date_end = Carbon::now();
        $this->duree_traitement_milli = $this->date_start->diffInMilliseconds($this->date_end);
        $this->duree_traitement_micro = $this->date_start->diffInMicroseconds($this->date_end);

        $this->save();

    }
}
