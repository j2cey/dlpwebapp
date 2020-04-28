<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use DB;
use Charts;
use App\Charts\RequeteChart;
use App\TypeDemande;

use App\Requete;
use App\Demandeur;
use App\Autorisation;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index() {

        Carbon::setLocale('fr');

        $type_demande_alimentaire = TypeDemande::where('code', '1')->first();
        $type_demande_sante = TypeDemande::where('code', '2')->first();
        $type_demande_urgence = TypeDemande::where('code', '3')->first();

        $requetes_dujour_par_heure = DB::table('requetes')
          ->select(DB::raw('count(*) as count, HOUR(created_at) as hour'))
          ->whereDate('created_at', '=', Carbon::now()->toDateString())
          ->groupBy('hour')
          ->orderBy('hour')
          ->pluck('count','hour');
        // La répartition des autorisations dans la journée (chaque heure) et par type
        $consultations_dujour_par_heure = DB::table('requetes')
          ->select(DB::raw('count(*) as count, HOUR(created_at) as hour'))
          ->whereDate('created_at', '=', Carbon::now()->toDateString())
          ->where('type_demande_id', 4)
          ->groupBy('hour')
          ->orderBy('hour')
          ->pluck('count','hour');
        //dd($consultations_dujour_par_heure);
        $autorisations_dujour_par_heure = DB::table('autorisations')
          ->select(DB::raw('count(*) as count, HOUR(created_at) as hour'))
          ->whereDate('created_at', '=', Carbon::now()->toDateString())
          ->groupBy('hour')
          ->pluck('count','hour');
        $autorisationsalimentaires_dujour_par_heure = DB::table('autorisations')
          ->select(DB::raw('count(*) as count, HOUR(created_at) as hour'))
          ->whereDate('created_at', '=', Carbon::now()->toDateString())
          ->where('type_demande_id', 1)
          ->groupBy('hour')
          ->pluck('count','hour');
        //dd($autorisationsalimentaires_dujour_par_heure,$autorisationsalimentaires_dujour_par_heure->sum());
        $autorisationssantes_dujour_par_heure = DB::table('autorisations')
          ->select(DB::raw('count(*) as count, HOUR(created_at) as hour'))
          ->whereDate('created_at', '=', Carbon::now()->toDateString())
          ->where('type_demande_id', 2)
          ->groupBy('hour')
          ->pluck('count','hour');
        $autorisationsurgences_dujour_par_heure = DB::table('autorisations')
          ->select(DB::raw('count(*) as count, HOUR(created_at) as hour'))
          ->whereDate('created_at', '=', Carbon::now()->toDateString())
          ->where('type_demande_id', 3)
          ->groupBy('hour')
          ->pluck('count','hour');

        //dd($consultations_dujour_par_heure,$autorisations_dujour_par_heure,$autorisationsalimentaires_dujour_par_heure,$autorisationssantes_dujour_par_heure,$autorisationsurgences_dujour_par_heure);

        $heures_du_jour = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24];

        $requetesetconsultations_dujour_chart = new RequeteChart;
        $requetesetconsultations_dujour_chart->labels($heures_du_jour);
        $requetesetconsultations_dujour_chart->dataset('Requetes ('.$requetes_dujour_par_heure->sum().')', 'line', $this->getRangedDataByDay($requetes_dujour_par_heure))
          ->backgroundColor('rgba(105, 0, 132, .2)')
          ->color('rgba(200, 99, 132, .7)');
        $requetesetconsultations_dujour_chart->dataset('Consultations ('.$consultations_dujour_par_heure->sum().')', 'line', $this->getRangedDataByDay($consultations_dujour_par_heure))
          ->backgroundColor('rgba(0, 137, 132, .2)')
          ->color('rgba(0, 10, 130, .7)');

        $autorisations_dujour_chart = new RequeteChart;
        $autorisations_dujour_chart->labels($heures_du_jour);

        $autorisations_dujour_chart->dataset('Alimentaire', 'bar', $this->getRangedDataByDay($autorisationsalimentaires_dujour_par_heure))
          ->backgroundColor('rgba(255, 206, 86, 1)') // 0.2)')
          ->color('rgba(255, 206, 86, 1)');
        $autorisations_dujour_chart->dataset('Santé', 'bar', $this->getRangedDataByDay($autorisationssantes_dujour_par_heure))
          ->backgroundColor('rgba(75, 192, 192, 1)') // 0.2)')
          ->color('rgba(75, 192, 192, 1)');
        $autorisations_dujour_chart->dataset('Urgence', 'bar', $this->getRangedDataByDay($autorisationsurgences_dujour_par_heure))
          ->backgroundColor('rgba(255, 99, 132, 1)') // 0.2)')
          ->color('rgba(255,99,132,1)');

        // Autorisations Recap Hebdo
        $alimentaire_hebdo = Autorisation::whereBetween('date_debut', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
          ->where('type_demande_id', 1)
          ->count();
        $sante_hebdo = Autorisation::whereBetween('date_debut', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
          ->where('type_demande_id', 2)
          ->count();
        $urgence_hebdo = Autorisation::whereBetween('date_debut', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
          ->where('type_demande_id', 3)
          ->count();

        $autorisations_hebdo_chart = new RequeteChart;
        $autorisations_hebdo_chart->labels(['Alimentaire','Santé','Urgence']);
        $autorisations_hebdo_chart->dataset('Autorisations Hebdo', 'doughnut', [$alimentaire_hebdo,$sante_hebdo,$urgence_hebdo])->backgroundColor([
          'rgb(255, 206, 86)',
          'rgb(75, 192, 192)',
          'rgb(255, 99, 132)',
        ]);

        $tophebdodemandeurs_alim = DB::table("autorisations")
          ->select(DB::raw("COUNT(id) count, demandeur"))
          ->whereBetween('date_debut', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
          ->where('type_demande_id', 1)
          ->groupBy("demandeur")
          ->havingRaw("COUNT(id) > 2")
          ->get();
        //dd($tophebdodemandeurs_alim->count());
        $tophebdodemandeurs_sante = DB::table("autorisations")
            ->select(DB::raw("COUNT(id) count, demandeur"))
            ->whereBetween('date_debut', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
            ->where('type_demande_id', 2)
            ->groupBy("demandeur")
            ->havingRaw("COUNT(id) > 2")
            ->get();
        $tophebdodemandeurs_urg = DB::table("autorisations")
              ->select(DB::raw("COUNT(id) count, demandeur"))
              ->whereBetween('date_debut', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
              ->where('type_demande_id', 3)
              ->groupBy("demandeur")
              ->havingRaw("COUNT(id) > 2")
              ->get();

        // Autorisation Accordée - 1
        // Autorisation Accordée (échue) - 3
        $resumhebdodemandes_autorisationaccordee = DB::table("requetes")
              ->select(DB::raw("COUNT(requetes.id) count, type_demandes.name typedemande"))
              ->join('type_demandes', 'type_demandes.id', '=', 'requetes.type_demande_id')
              ->whereBetween('requetes.created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
              ->whereIn('requetes.type_reponse_id', [1,3])
              ->groupBy("typedemande")
              ->pluck('count','typedemande');
        // Echec Demande - Autorisation En Cours
        $resumhebdodemandes_autorisationencours = DB::table("requetes")
              ->select(DB::raw("COUNT(requetes.id) count, type_demandes.name typedemande"))
              ->join('type_demandes', 'type_demandes.id', '=', 'requetes.type_demande_id')
              ->whereBetween('requetes.created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
              ->where('requetes.type_reponse_id', 6)
              ->groupBy("typedemande")
              ->pluck('count','typedemande');
        // Demande Hors Periode Autorisée
        $resumhebdodemandes_demandehorsperiode = DB::table("requetes")
              ->select(DB::raw("COUNT(requetes.id) count, type_demandes.name typedemande"))
              ->join('type_demandes', 'type_demandes.id', '=', 'requetes.type_demande_id')
              ->whereBetween('requetes.created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
              ->where('requetes.type_reponse_id', 7)
              ->groupBy("typedemande")
              ->pluck('count','typedemande');
        // Plafond Hebdomadaire atteint
        $resumhebdodemandes_plafondhebdoatteint = DB::table("requetes")
              ->select(DB::raw("COUNT(requetes.id) count, type_demandes.name typedemande"))
              ->join('type_demandes', 'type_demandes.id', '=', 'requetes.type_demande_id')
              ->whereBetween('requetes.created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
              ->where('requetes.type_reponse_id', 8)
              ->groupBy("typedemande")
              ->pluck('count','typedemande');

        $resumhebdodemandes_typedemandeinactif = DB::table("requetes")
              ->select(DB::raw("COUNT(requetes.id) count, type_demandes.name typedemande"))
              ->join('type_demandes', 'type_demandes.id', '=', 'requetes.type_demande_id')
              ->whereBetween('requetes.created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
              ->where('requetes.type_reponse_id', 10)
              ->groupBy("typedemande")
              ->pluck('count','typedemande');

        $resultatshebdo_demandealim = $this->getResultsHebdoForTypeDemande($resumhebdodemandes_autorisationaccordee,$resumhebdodemandes_autorisationencours,$resumhebdodemandes_demandehorsperiode,$resumhebdodemandes_plafondhebdoatteint,$resumhebdodemandes_typedemandeinactif,$type_demande_alimentaire);
        $resultatshebdo_demandesante = $this->getResultsHebdoForTypeDemande($resumhebdodemandes_autorisationaccordee,$resumhebdodemandes_autorisationencours,$resumhebdodemandes_demandehorsperiode,$resumhebdodemandes_plafondhebdoatteint,$resumhebdodemandes_typedemandeinactif,$type_demande_sante);
        $resultatshebdo_demandeurg = $this->getResultsHebdoForTypeDemande($resumhebdodemandes_autorisationaccordee,$resumhebdodemandes_autorisationencours,$resumhebdodemandes_demandehorsperiode,$resumhebdodemandes_plafondhebdoatteint,$resumhebdodemandes_typedemandeinactif,$type_demande_urgence);

        $resumhebdodemandes_parresultat_chart = new RequeteChart;
        $resumhebdodemandes_parresultat_chart->labels(['Autorisation Accordée','Autorisation En Cours','Demande Hors Periode','Plafond Hebdo atteint','Type Demande Inactif']);

        $resumhebdodemandes_parresultat_chart->dataset('Alimentaire', 'radar', $resultatshebdo_demandealim)
          ->backgroundColor('rgba(255, 206, 86, .2)') // 0.2)')
          ->color('rgba(255, 206, 86, .7)');
        $resumhebdodemandes_parresultat_chart->dataset('Santé', 'radar', $resultatshebdo_demandesante)
          ->backgroundColor('rgba(75, 192, 192, .2)') // 0.2)')
          ->color('rgba(75, 192, 192, .7)');
        $resumhebdodemandes_parresultat_chart->dataset('Urgence', 'radar', $resultatshebdo_demandeurg)
          ->backgroundColor('rgba(255, 99, 132, .2)') // 0.2)')
          ->color('rgba(255,99,132,.7)');

          $resumhebdo_resultats_chart = new RequeteChart;
          $resumhebdo_resultats_chart->labels(['Autorisation Accordée','Autorisation En Cours','Demande Hors Periode','Plafond Hebdo atteint','Type Demande Inactif']);
          $resumhebdo_resultats_chart->dataset('Résultats Hebdo', 'polarArea', [$resumhebdodemandes_autorisationaccordee->sum(),$resumhebdodemandes_autorisationencours->sum(),$resumhebdodemandes_demandehorsperiode->sum(),$resumhebdodemandes_plafondhebdoatteint->sum(),$resumhebdodemandes_typedemandeinactif->sum()])
            ->backgroundColor([
            'rgb(255, 206, 86,.2)',
            'rgb(75, 192, 192,.2)',
            'rgb(255, 99, 132,.2)',
            'rgba(0, 10, 130,.2)',
            'rgba(127, 63, 191,.2)'])
            ->color([
            'rgb(255, 206, 86,.7)',
            'rgb(75, 192, 192,.7)',
            'rgb(255, 99, 132,.7)',
            'rgba(0, 10, 130,.7)',
            'rgba(127, 63, 191,.7)']
        );

        //return $reqs;
        return view('welcome',compact(
          'requetesetconsultations_dujour_chart','autorisations_dujour_chart','consultations_dujour_chart','autorisations_dujour_chart',
          'consultations_dujour_par_heure','autorisationsalimentaires_dujour_par_heure',
           'autorisationssantes_dujour_par_heure','autorisationsurgences_dujour_par_heure',
          'alimentaire_hebdo','sante_hebdo','urgence_hebdo','autorisations_hebdo_chart',
          'tophebdodemandeurs_alim','tophebdodemandeurs_sante','tophebdodemandeurs_urg',
          'resumhebdodemandes_parresultat_chart','resumhebdo_resultats_chart'));
    }

    private function getRangedDataByDay($data) {
      $data_rst = [];
      for ($i=1; $i < 25 ; $i++) {
          $data_rst[] = isset($data[$i]) ? $data[$i] : 0;
      }

      return $data_rst;
    }

    private function getResultsHebdoForTypeDemande($resumhebdo_autorisationaccordee,$resumhebdo_autorisationencours,$resumhebdo_demandehorsperiode,$resumhebdo_plafondhebdoatteint,$resumhebdodemandes_typedemandeinactif,$type_demande) {
      return [
        isset($resumhebdo_autorisationaccordee[$type_demande->name]) ? $resumhebdo_autorisationaccordee[$type_demande->name] : 0,
        isset($resumhebdo_autorisationencours[$type_demande->name]) ? $resumhebdo_autorisationencours[$type_demande->name] : 0,
        isset($resumhebdo_demandehorsperiode[$type_demande->name]) ? $resumhebdo_demandehorsperiode[$type_demande->name] : 0,
        isset($resumhebdo_plafondhebdoatteint[$type_demande->name]) ? $resumhebdo_plafondhebdoatteint[$type_demande->name] : 0,
        isset($resumhebdodemandes_typedemandeinactif[$type_demande->name]) ? $resumhebdodemandes_typedemandeinactif[$type_demande->name] : 0,
      ];
    }
}
