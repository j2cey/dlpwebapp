<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Requete;
use App\Demandeur;
use App\Autorisation;

class DefaultController extends Controller
{
    public function defaultrequest($reqtype, $phonenum) {
      //dd($reqtype, $phonenum);

      $validite_heure = 0;
      $ecart_autorisation_jrs = 0;
      $date_fin = date('Y-m-d H:i:s');

      //$request_parsed = -1;
      //$request_msg = "";

      // 1. Enregistrement de la requeete
      $curr_requete = Requete::create([
        'reqtype' => $reqtype,
        'phonenum' => $phonenum,
      ]);

      //dd($curr_requete);

      // 2. Essaie de trouver le demandeur
      $demandeur = Demandeur::where('phonenum', $curr_requete->phonenum)->first();
      if (is_null($demandeur)) {
        $demandeur = Demandeur::create([
          'phonenum' => $phonenum,
          'is_requesting' => true,
        ]);
      }
      $curr_requete->demandeur_id = $demandeur->id;

      //dd($curr_requete, $demandeur);

      // 3. Analyse de la Requete
      if ($reqtype == '1') {
        $curr_requete->req_code = 1;
        $curr_requete->msg = "Deplacement alimentaire autorise";

        $validite_heure = 3;
        $date_fin = date('Y-m-d H:i:s', strtotime('now +3 hour'));
        $ecart_autorisation_jrs = 3;
      } elseif($reqtype == '2') {
        $curr_requete->req_code = 2;
        $curr_requete->msg = "Deplacement de sante autorise";

        $validite_heure = 2;
        $date_fin = date('Y-m-d H:i:s', strtotime('now +2 hour'));
        $ecart_autorisation_jrs = 2;
      } elseif($reqtype == '3') {
        $curr_requete->req_code = 3;
        $curr_requete->msg = "Deplacement d urgence autorise";

        $validite_heure = 24;
        $date_fin = date('Y-m-d H:i:s', strtotime('now +24 hour'));
        $ecart_autorisation_jrs = 0;
      } elseif($reqtype == '4') {
        $curr_requete->req_code = 4;
        $curr_requete->msg = $demandeur->consultation();

        $validite_heure = 0;
        $ecart_autorisation_jrs = 0;

      } else {
        $curr_requete->req_code = -1;
        $demandeur->is_requesting = false;
        $curr_requete->msg = "Bad Request";
      }

      // 4. Octroyer l autorisation
      if ($curr_requete->req_code > -1 && $curr_requete->req_code < 4) {

        if ($demandeur->aAutorisationEnCours()) {
          // Le demandeur a deja une autorisation non echue
          $curr_requete->req_code = -2;
          $curr_requete->msg = "Vous avez une autorisation en cours";
        } else {
          // On donne une nouvelle autorisation au Demandeur
          $new_autorisation = Autorisation::create([
            'demandeur_id' => $demandeur->id,
            'requete_id' => $curr_requete->id,
            'code' => $curr_requete->req_code,
            'msg' => $curr_requete->msg,
            'is_active' => true,
            'date_debut' => now(),
            'date_fin' => $date_fin,
          ]);

          $demandeur->is_requesting = false;
        }
      }

      // A la fin
      $curr_requete->save();
      $demandeur->save();

      return response()->json([
             'message' => $curr_requete->msg
         ]);
    }
}
