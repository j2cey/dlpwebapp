<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Requete;
use App\TypeDemande;
use App\TypeReponse;
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

      Carbon::setLocale('fr');
      //Carbon::setWeekStartsAt(Carbon::MONDAY);

      $date_debut = Carbon::now();
      $date_intervaldemande_debut = Carbon::create($date_debut->year, $date_debut->month, $date_debut->day, 06, 00, 00);
      $date_intervaldemande_fin = Carbon::create($date_debut->year, $date_debut->month, $date_debut->day, 19, 30, 00);
      $date_fin = Carbon::now();

      $date_debut->addHours(1);
      //$date_intervaldemande_debut->addHours(1);
      //$date_intervaldemande_fin->addHours(1);
      $date_fin->addHours(1);

      $msg_result = "";
      $msg_autorisation = "";

      // Type Demande
      $type_demande = TypeDemande::where('code', $reqtype)->get()->first();
      if (is_null($type_demande)) {
          $type_demande = TypeDemande::where('code', "5")->get()->first();
      }

      // Creation Nouvel objet requete
      $curr_requete = new Requete();
      $curr_requete->reqtype = $reqtype;
      $curr_requete->phonenum = $phonenum;
      $curr_requete->type_demande_id = $type_demande->id;
      $curr_requete->date_start = Carbon::now();
      $curr_requete->created_at = Carbon::now()->addHours(1);
      $curr_requete->updated_at = Carbon::now()->addHours(1);

      // $startOfWeek = $date_debut->startOfWeek();
      // $endOfWeek = $date_debut->endOfWeek();

      //dd([Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek(),Carbon::parse('last monday')->startOfDay()->addHours(1),Carbon::parse('next sunday')->endOfDay()->addHours(1)]);

      if ($type_demande->code == "4") {
          // Consultation
          $autorisation_en_cours = Autorisation::where('demandeur', $phonenum)->where('is_active', 1)->with('type_demande')->first();

          if (is_null($autorisation_en_cours)) {
              //$msg_result = "Aucune Autorisation En Cours";
              $type_reponse = TypeReponse::where('code', -6)->get()->first();
              $msg_result = $type_reponse->msg_reponse;
          } else {
              $type_reponse = TypeReponse::where('code', 2)->get()->first();
              $msg_result = $autorisation_en_cours
                ->type_demande->getMessageConsultation($autorisation_en_cours->date_debut, $autorisation_en_cours->date_fin);
          }

          $curr_requete->Finalize($type_reponse->id);
      } elseif ($type_demande->code == "1" || $type_demande->code == "2" || $type_demande->code == "3") {

          $autorisation_en_cours = Autorisation::where('demandeur', $phonenum)->where('is_active', 1)->with('type_demande')->first();

          if (is_null($autorisation_en_cours)) {
              $autorisation_hebdo_obtenues = Autorisation::whereBetween('date_debut', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
                ->where('type_demande_id', $type_demande->id)
                ->where('demandeur', $phonenum)
                ->count();
              if ($autorisation_hebdo_obtenues >= $type_demande->plafond_hebdo) {
                  // Plafond Hebdo atteint
                  //$msg_result = "Désolé. Vous avez atteint le Plafond Hebdomadaire pour ce type d autorisation";
                  $type_reponse = TypeReponse::where('code', -5)->get()->first();
                  $msg_result = $type_reponse->msg_reponse;
                  $curr_requete->Finalize($type_reponse->id);
              } else {

                  $debut_in_interval = ($date_debut->between($date_intervaldemande_debut, $date_intervaldemande_fin));

                  if ($debut_in_interval) {
                      $heures_restantes = $date_debut->diffInHours($date_intervaldemande_fin, false);
                      if ($heures_restantes < $type_demande->validite_heure) {
                          // On assigne la date limite
                          $date_fin = $date_intervaldemande_fin;
                      } else {
                          // On recupère la date de fin normale
                          $date_fin->addHours($type_demande->validite_heure);
                      }

                      $msg_result =  $type_demande->getMessageSucces($date_debut, $date_fin);

                      // On donne une nouvelle autorisation au Demandeur
                      $type_reponse = TypeReponse::where('code', 1)->get()->first();
                      $curr_requete->Finalize($type_reponse->id);

                      $new_autorisation = Autorisation::create([
                        'demandeur' => $phonenum,
                        'requete_id' => $curr_requete->id,
                        'type_demande_id' => $type_demande->id,
                        'is_active' => true,
                        'date_debut' => $date_debut,
                        'date_fin' => $date_fin,
                        'created_at' => Carbon::now()->addHours(1),
                        'updated_at' => Carbon::now()->addHours(1),
                      ]);
                  } else {
                      // Heure debut demandes non-atteinte
                      $type_reponse = TypeReponse::where('code', -4)->get()->first();
                      $msg_result = $type_reponse->msg_reponse;
                      $curr_requete->Finalize($type_reponse->id);
                  }
              }
          } else {
              // Le demandeur a deja une autorisation non echue
              $type_reponse = TypeReponse::where('code', -3)->get()->first();
              $msg_result = $autorisation_en_cours->type_demande->getMessageConsultation($autorisation_en_cours->date_debut, $autorisation_en_cours->date_fin);
              $curr_requete->Finalize($type_reponse->id);
          }
      } else {
          // Bad Requete
          $type_reponse = TypeReponse::where('code', -2)->get()->first();
          $msg_result = $type_reponse->msg_reponse;
          $curr_requete->Finalize($type_reponse->id);
      }

      return response()->json([
        'message' => $msg_result
      ]);
    }
}
