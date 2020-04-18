<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Requete;
use App\Demandeur;
use App\Autorisation;
use Carbon\Carbon;

class DefaultController extends Controller
{
    public function defaultrequest($reqtype, $phonenum) {
      //dd($reqtype, $phonenum);

      $validite_heure = 0;
      $ecart_autorisation_jrs = 0;

      Carbon::setLocale('fr');

      $date_debut = Carbon::now();
      $date_limite = Carbon::create($date_debut->year, $date_debut->month, $date_debut->day, 19, 30, 00);
      $date_fin = Carbon::now();

      $msg_autorisation = "";

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

      // 3. Analyse de la Requete
      if ($reqtype == '1') {
        $curr_requete->req_code = 1;
        $validite_heure = 3;

        $curr_requete->msg = "votre demande de déplacement alimentaire du " . $date_debut->translatedFormat('jS F Y \\a H:i');
        $msg_autorisation = "vous avez une autorisation de déplacement alimentaire du " . $date_debut->translatedFormat('jS F Y \\a H:i');

        $ecart_autorisation_jrs = 3;
      } elseif($reqtype == '2') {
        $curr_requete->req_code = 2;

        $validite_heure = 2;
        $curr_requete->msg = "votre demande de déplacement de santé du " . $date_debut->translatedFormat('jS F Y \\a H:i');
        $msg_autorisation = "vous avez une autorisation de déplacement de santé du " . $date_debut->translatedFormat('jS F Y \\a H:i');

        $ecart_autorisation_jrs = 2;
      } elseif($reqtype == '3') {
        $curr_requete->req_code = 3;
        $validite_heure = 2;

        $curr_requete->msg = "votre demande de déplacement d urgence du " . $date_debut->translatedFormat('jS F Y \\a H:i');
        $msg_autorisation = "vous avez une autorisation de déplacement d urgence du " . $date_debut->translatedFormat('jS F Y \\a H:i');

        $validite_heure = 24;
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
        $autorisation_en_cours = $demandeur->autorisationEnCours();
        if (is_null($autorisation_en_cours)) {

            $heures_restantes = $date_debut->diffInHours($date_limite);

            if ($heures_restantes < 0) {
                // Heure limite atteinte
                $curr_requete->req_code = -3;
                $demandeur->is_requesting = false;
                $curr_requete->msg = "Désolé. Heure limite atteinte pour les demandes d autorisation";
            } else {
                if ($heures_restantes < $validite_heure) {
                    // On assigne la date limite
                    $date_fin = $date_limite;
                } else {
                    // On recupère la date de fin normale
                    $date_fin->addHours($validite_heure);
                }

                $curr_requete->msg = $curr_requete->msg . " au " . $date_fin->translatedFormat('jS F Y \\a H:i') . " a ete validee";
                $msg_autorisation = $msg_autorisation . " au " . $date_fin->translaj2tedFormat('jS F Y \\a H:i');

                // On donne une nouvelle autorisation au Demandeur
                $new_autorisation = Autorisation::create([
                  'demandeur_id' => $demandeur->id,
                  'requete_id' => $curr_requete->id,
                  'code' => $curr_requete->req_code,
                  'msg' => $msg_autorisation,
                  'is_active' => true,
                  'date_debut' => $date_debut,
                  'date_fin' => $date_fin,
                ]);

                $demandeur->is_requesting = false;
            }
        } else {
            // Le demandeur a deja une autorisation non echue
            $curr_requete->req_code = -2;
            $curr_requete->msg = $autorisation_en_cours->msg . " en cours";
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
