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

        $chart = new RequeteChart;
        $chart->labels(['One', 'Two', 'Three', 'Four']);
        $chart->dataset('My dataset 1', 'line', [1, 2, 3, 4]);

        $browser_total_raw = DB::raw('count(*) as total');
        $reqs = Requete::getQuery()
                     ->select('created_at', $browser_total_raw)
                     ->groupBy('created_at')
                     ->pluck('created_at','total');

        $reqs = Requete::selectRaw("COUNT(*) number, DATE_FORMAT(created_at, '%Y %m %e') date")
                 ->groupBy('date')
                 ->pluck('nombre','date');

        $reqs = Requete::pluck();

        $reqs_byday_bytype = Requete::selectRaw("DATE_FORMAT(created_at, '%Y %m %e') date, reqtype_name as type, COUNT(*) nombre")
                  ->groupBy('date','reqtype_name')
                  ->get();
        //return $reqs;
        return view('welcome',compact('chart'));
    }
}
