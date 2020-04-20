<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Requete;
use App\Demandeur;
use App\Autorisation;
use Carbon\Carbon;

class DefaultController extends Controller
{
    public function generaterequest($nbrequetes) {

        $reqtype_arr = ["1","2","3","4","xxx"];
        $nb_nums = 100;
        $phonenum_arr = [];

        for ($i=0; $i < $nb_nums; $i++) {
            if ($i < 10) {
              $phonenum_arr[] = "06600000" . $i;
            } elseif ($i < 100) {
              $phonenum_arr[] = "0660000" . $i;
            } else {
              $phonenum_arr[] = "066000" . $i;
            }
        }

        for ($i=0; $i < $nbrequetes; $i++) {
            $reqtype = "";
            $phonenum = "";

            $k = array_rand($reqtype_arr);
            $reqtype = $reqtype_arr[$k];

            $k = array_rand($phonenum_arr);
            $phonenum = $phonenum_arr[$k];

            $this->defaultrequest($reqtype, $phonenum);
        }

        return response()->json([
          'message' => $nbrequetes . " requetes executee avec succes"
        ]);
    }

    public function defaultrequest($reqtype, $phonenum) {

      $validite_heure = 0;
      $plafond_hebdo = 0;
      $reqtype_name_arr = [
        "1"=>"Deplacement Alimentaire",
        "2"=>"Deplacement Sante",
        "3"=>"Deplacement Urgence",
        "4"=>"Consultation Autorisation"];

      Carbon::setLocale('fr');

      $date_debut = Carbon::now();
      $date_intervaldemande_debut = Carbon::create($date_debut->year, $date_debut->month, $date_debut->day, 06, 00, 00);
      $date_intervaldemande_fin = Carbon::create($date_debut->year, $date_debut->month, $date_debut->day, 19, 30, 00);
      $date_fin = Carbon::now();

      $date_debut->addHours(1);
      //$date_intervaldemande_debut->addHours(1);
      //$date_intervaldemande_fin->addHours(1);
      $date_fin->addHours(1);

      $msg_autorisation = "";

      // 1. Enregistrement de la requeete
      $curr_requete = Requete::create([
        'reqtype' => $reqtype,
        'phonenum' => $phonenum,
        'reqtype_name' => $reqtype_name_arr[$reqtype] ?? "Bad Request",
        'date_start' => Carbon::now(),
        'created_at' => Carbon::now()->addHours(1),
        'updated_at' => Carbon::now()->addHours(1),
      ]);

      // 2. Essaie de trouver le demandeur
      $demandeur = Demandeur::where('phonenum', $curr_requete->phonenum)->first();
      if (is_null($demandeur)) {
        $demandeur = Demandeur::create([
          'phonenum' => $phonenum,
          'is_requesting' => true,
          'created_at' => Carbon::now()->addHours(1),
          'updated_at' => Carbon::now()->addHours(1),
        ]);
      }
      $curr_requete->demandeur_id = $demandeur->id;

      // 3. Analyse de la Requete
      if ($reqtype == '1') {
        $curr_requete->resp_code = 1;
        $validite_heure = 3;
        $plafond_hebdo = 3;

        $curr_requete->msg = "Votre demande de déplacement alimentaire du " . $date_debut->translatedFormat('jS F Y \\à H:i');
        $msg_autorisation = "Vous avez une autorisation de déplacement alimentaire du " . $date_debut->translatedFormat('jS F Y \\à H:i');

      } elseif($reqtype == '2') {
        $curr_requete->resp_code = 2;
        $validite_heure = 2;
        $plafond_hebdo = 2;

        $curr_requete->msg = "Votre demande de déplacement de santé du " . $date_debut->translatedFormat('jS F Y \\à H:i');
        $msg_autorisation = "Vous avez une autorisation de déplacement de santé du " . $date_debut->translatedFormat('jS F Y \\à H:i');

      } elseif($reqtype == '3') {
        $curr_requete->resp_code = 3;
        $validite_heure = 2;
        $plafond_hebdo = 24;

        $curr_requete->msg = "Votre demande de déplacement d urgence du " . $date_debut->translatedFormat('jS F Y \\à H:i');
        $msg_autorisation = "Vous avez une autorisation de déplacement d urgence du " . $date_debut->translatedFormat('jS F Y \\à H:i');

      } elseif($reqtype == '4') {
        $curr_requete->resp_code = 4;
        $curr_requete->msg = $demandeur->consultation();

        $validite_heure = 0;
        $plafond_hebdo = 0;

      } else {
        $curr_requete->resp_code = -1;
        $demandeur->is_requesting = false;
        $curr_requete->msg = "Bad Request";
      }

      // 4. Octroyer l autorisation
      if ($curr_requete->resp_code > -1 && $curr_requete->resp_code < 4) {
        $autorisation_en_cours = $demandeur->autorisationEnCours();
        if (is_null($autorisation_en_cours)) {

            if ($demandeur->plafondAutorisationsHebdoAtteint($curr_requete->resp_code, $plafond_hebdo)) {
                // Plafond Hebdo atteint
                $curr_requete->resp_code = -4;
                $demandeur->is_requesting = false;
                $curr_requete->msg = "Désolé. Vous avez atteint le Plafond Hebdomadaire pour ce type d autorisation";
            } else {

                $debut_in_interval = ($date_debut->between($date_intervaldemande_debut, $date_intervaldemande_fin));

                if ($debut_in_interval) {
                    $heures_restantes = $date_debut->diffInHours($date_intervaldemande_fin, false);
                    if ($heures_restantes < $validite_heure) {
                        // On assigne la date limite
                        $date_fin = $date_limite;
                    } else {
                        // On recupère la date de fin normale
                        $date_fin->addHours($validite_heure);
                    }

                    $curr_requete->msg = $curr_requete->msg . " au " . $date_fin->translatedFormat('jS F Y \\à H:i') . " a ete validée";
                    $msg_autorisation = $msg_autorisation . " au " . $date_fin->translatedFormat('jS F Y \\à H:i');

                    // On donne une nouvelle autorisation au Demandeur
                    $new_autorisation = Autorisation::create([
                      'demandeur_id' => $demandeur->id,
                      'requete_id' => $curr_requete->id,
                      'code' => $curr_requete->resp_code,
                      'msg' => $msg_autorisation,
                      'is_active' => true,
                      'date_debut' => $date_debut,
                      'date_fin' => $date_fin,
                      'created_at' => Carbon::now()->addHours(1),
                      'updated_at' => Carbon::now()->addHours(1),
                    ]);

                    $demandeur->is_requesting = false;
                } else {
                    // Heure debut demandes non-atteinte
                    $curr_requete->resp_code = -3;
                    $demandeur->is_requesting = false;
                    $curr_requete->msg = "Désolé. Les demandes d autorisation ne sont pas déjà disponibles";
                }
            }
        } else {
            // Le demandeur a deja une autorisation non echue
            $curr_requete->resp_code = -2;
            $demandeur->is_requesting = false;
            $curr_requete->msg = $autorisation_en_cours->msg . " en cours";
        }
      }

      // A la fin
      $curr_requete->date_end = Carbon::now();
      $curr_requete->duree_traitement_milli = $curr_requete->date_start->diffInMilliseconds($curr_requete->date_end);
      $curr_requete->duree_traitement_micro = $curr_requete->date_start->diffInMicroseconds($curr_requete->date_end);
      $curr_requete->save();

      $demandeur->save();

      return response()->json([
        'message' => $curr_requete->msg
      ]);
    }
}
