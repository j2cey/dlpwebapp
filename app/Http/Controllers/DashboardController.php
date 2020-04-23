<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use DB;
use Charts;
use App\Charts\RequeteChart;

use App\Requete;
use App\Demandeur;
use App\Autorisation;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index() {

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
        $alimentaire_hebdo = Autorisation::whereBetween('date_debut', [Carbon::parse('last monday')->startOfDay(),Carbon::parse('next sunday')->endOfDay()])
          ->where('type_demande_id', 1)
          ->count();
        $sante_hebdo = Autorisation::whereBetween('date_debut', [Carbon::parse('last monday')->startOfDay(),Carbon::parse('next sunday')->endOfDay()])
            ->where('type_demande_id', 2)
            ->count();
        $urgence_hebdo = Autorisation::whereBetween('date_debut', [Carbon::parse('last monday')->startOfDay(),Carbon::parse('next sunday')->endOfDay()])
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
          ->whereBetween('date_debut', [Carbon::parse('last monday')->startOfDay(),Carbon::parse('next sunday')->endOfDay()])
          ->where('type_demande_id', 1)
          ->groupBy("demandeur")
          ->havingRaw("COUNT(id) > 2")
          ->get();
        //dd($tophebdodemandeurs_alim->count());
        $tophebdodemandeurs_sante = DB::table("autorisations")
            ->select(DB::raw("COUNT(id) count, demandeur"))
            ->whereBetween('date_debut', [Carbon::parse('last monday')->startOfDay(),Carbon::parse('next sunday')->endOfDay()])
            ->where('type_demande_id', 2)
            ->groupBy("demandeur")
            ->havingRaw("COUNT(id) > 2")
            ->get();
        $tophebdodemandeurs_urg = DB::table("autorisations")
              ->select(DB::raw("COUNT(id) count, demandeur"))
              ->whereBetween('date_debut', [Carbon::parse('last monday')->startOfDay(),Carbon::parse('next sunday')->endOfDay()])
              ->where('type_demande_id', 3)
              ->groupBy("demandeur")
              ->havingRaw("COUNT(id) > 2")
              ->get();
        //dd($tophebdodemandeurs);

        //return $reqs;
        return view('welcome',compact(
          'requetesetconsultations_dujour_chart','autorisations_dujour_chart','consultations_dujour_chart','autorisations_dujour_chart',
          'consultations_dujour_par_heure','autorisationsalimentaires_dujour_par_heure',
           'autorisationssantes_dujour_par_heure','autorisationsurgences_dujour_par_heure',
          'alimentaire_hebdo','sante_hebdo','urgence_hebdo','autorisations_hebdo_chart',
          'tophebdodemandeurs_alim','tophebdodemandeurs_sante','tophebdodemandeurs_urg'));
    }

    private function getRangedDataByDay($data) {
      $data_rst = [];
      for ($i=1; $i < 25 ; $i++) {
          $data_rst[] = isset($data[$i]) ? $data[$i] : 0;
      }

      return $data_rst;
    }
}
