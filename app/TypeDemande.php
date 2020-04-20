<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TypeDemande extends Model
{
    public function getMessageSucces($date_deb = null, $date_fin = null) {
        return $this->getMessage($this->msg_succes, $date_deb, $date_fin);
    }

    public function getMessageConsultation($date_deb = null, $date_fin = null) {
        return $this->getMessage($this->msg_consultation, $date_deb, $date_fin);
    }

    public function getMessage($msg, $date_deb = null, $date_fin = null) {
        if (!(is_null($date_deb))) {
          $date_deb_c = Carbon::parse($date_deb);
          //$time_deb = (explode(" ", $date_deb))[1];
          $msg = str_replace("DATEDEBUT", $date_deb_c->translatedFormat('jS F Y \\à H:i'), $msg);
        }
        if (!(is_null($date_fin))) {
          $date_fin_c = Carbon::parse($date_fin);
          //$time_fin = (explode(" ", $date_fin))[1];
          $msg = str_replace("DATEFIN", $date_fin_c->translatedFormat('jS F Y \\à H:i'), $msg);
        }
        return $msg;
    }
}
