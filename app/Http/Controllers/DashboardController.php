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

        $totalconsultation_dujour = Requete::whereDate('created_at', DB::raw('CURDATE()'))->where('type_demande_id', 4)->count();
        $totaldemandesalimentaire_dujour = Requete::whereDate('created_at', DB::raw('CURDATE()'))->where('type_demande_id', 1)->count();
        $totaldemandessante_dujour = Requete::whereDate('created_at', DB::raw('CURDATE()'))->where('type_demande_id', 2)->count();
        $totaldemandesurgences_dujour = Requete::whereDate('created_at', DB::raw('CURDATE()'))->where('type_demande_id', 3)->count();;

        $browser_total_raw = DB::raw('count(*) as total');
        $reqs = Requete::getQuery()
                     ->select('created_at', $browser_total_raw)
                     ->groupBy('created_at')
                     ->pluck('created_at','total');

        $reqs = Requete::selectRaw("COUNT(*) nombre, DATE_FORMAT(created_at, '%Y %m %e') date")
                 ->groupBy('date')
                 ->pluck('nombre','date');

        //$reqs = Requete::pluck();

        $reqs_byday_bytype = Requete::selectRaw("created_at date, type_demande_id as type, COUNT(*) nombre")
                  ->groupBy('date','type_demande_id')
                  ->pluck('nombre', 'date');

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

        $chart = new RequeteChart;
        $chart->labels($reqs_byday_bytype->keys());
        $chart->dataset('Nombre Requetes', 'bar', $reqs_byday_bytype->values())->backgroundColor('#8b0000');

        $heures_du_jour = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24];

        $requetes_dujour_chart = new RequeteChart;
        $requetes_dujour_chart->labels($requetes_dujour_par_heure->keys());
        $requetes_dujour_chart->dataset('Nombre Requetes', 'line', $requetes_dujour_par_heure->values())->backgroundColor('blue');

        $consultations_dujour_chart = new RequeteChart;
        $consultations_dujour_chart->labels($consultations_dujour_par_heure->keys());
        $consultations_dujour_chart->dataset('Nombre Requetes', 'line', $consultations_dujour_par_heure->values())->backgroundColor('blue');

        $requetesetconsultations_dujour_chart = new RequeteChart;
        $requetesetconsultations_dujour_chart->labels($heures_du_jour);
        $requetesetconsultations_dujour_chart->dataset('Requetes', 'bar', $requetes_dujour_par_heure->values())->backgroundColor('green');
        $requetesetconsultations_dujour_chart->dataset('Consultations', 'bar', $consultations_dujour_par_heure->values())->backgroundColor('orange');

        $autorisations_dujour_chart = new RequeteChart;
        $autorisations_dujour_chart->labels($heures_du_jour);
        $autorisations_dujour_chart->dataset('Nombre Requetes', 'bar', $autorisations_dujour_par_heure->values())->backgroundColor('purple');

        $autorisationsalimentaires_dujour_chart = new RequeteChart;
        $autorisationsalimentaires_dujour_chart->labels($heures_du_jour);
        $autorisationsalimentaires_dujour_chart->dataset('Nombre Requetes', 'bar', $autorisationsalimentaires_dujour_par_heure->values())->backgroundColor('green');

        $autorisationssantes_dujour_chart = new RequeteChart;
        $autorisationssantes_dujour_chart->labels($heures_du_jour);
        $autorisationssantes_dujour_chart->dataset('Nombre Requetes', 'bar', $autorisationssantes_dujour_par_heure->values())->backgroundColor('orange');

        $autorisationsurgences_dujour_chart = new RequeteChart;
        $autorisationsurgences_dujour_chart->labels($heures_du_jour);
        $autorisationsurgences_dujour_chart->dataset('Nombre Requetes', 'bar', $autorisationsurgences_dujour_par_heure->values())->backgroundColor('red');

        $autorisations_dujour_chart = new RequeteChart;
        $autorisations_dujour_chart->labels($heures_du_jour);

        $autorisations_dujour_chart->dataset('Autorisations Alimentaires', 'bar', $autorisationsalimentaires_dujour_par_heure->values())->backgroundColor('green');
        $autorisations_dujour_chart->dataset('Autorisations Santé', 'bar', $autorisationssantes_dujour_par_heure->values())->backgroundColor('orange');
        $autorisations_dujour_chart->dataset('Autorisations Urgence', 'bar', $autorisationsurgences_dujour_par_heure->values())->backgroundColor('red');

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
          'rgb(54, 162, 235)',
          'rgb(255, 99, 132)',
        ]);


        // $data = Autorisation::groupBy('type_demande_id')
        //     ->get('type_demande_id')
        //     ->map(function ($item) {
        //         // Return the number of persons with that age
        //         return count($item);
        //     });

        // $chart = new RequeteChart;
        // $chart->labels($data->keys());
        // $chart->dataset('My dataset', 'line', $data->values());

        $tophebdodemandeurs_alim = DB::table("autorisations")
          ->select(DB::raw("COUNT(id) count, demandeur"))
          ->where('type_demande_id', 1)
          ->groupBy("demandeur")
          ->havingRaw("COUNT(id) > 2")
          ->get();
        //dd($tophebdodemandeurs_alim->count());
        $tophebdodemandeurs_sante = DB::table("autorisations")
            ->select(DB::raw("COUNT(id) count, demandeur"))
            ->where('type_demande_id', 2)
            ->groupBy("demandeur")
            ->havingRaw("COUNT(id) > 2")
            ->get();
        $tophebdodemandeurs_urg = DB::table("autorisations")
              ->select(DB::raw("COUNT(id) count, demandeur"))
              ->where('type_demande_id', 3)
              ->groupBy("demandeur")
              ->havingRaw("COUNT(id) > 2")
              ->get();
        //dd($tophebdodemandeurs);

        //return $reqs;
        return view('welcome',compact(
            'requetesetconsultations_dujour_chart','autorisations_dujour_chart','consultations_dujour_chart','autorisations_dujour_chart','autorisationsalimentaires_dujour_chart','autorisationssantes_dujour_chart','autorisationsurgences_dujour_chart',
            'totalconsultation_dujour','totaldemandesalimentaire_dujour','totaldemandessante_dujour','totaldemandesurgences_dujour',
            'alimentaire_hebdo','sante_hebdo','urgence_hebdo','autorisations_hebdo_chart',
            'tophebdodemandeurs_alim','tophebdodemandeurs_sante','tophebdodemandeurs_urg'));
    }
}
