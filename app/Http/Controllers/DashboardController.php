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
        $browser_total_raw = DB::raw('count(*) as total');
        $reqs = Requete::getQuery()
                     ->select('created_at', $browser_total_raw)
                     ->groupBy('created_at')
                     ->pluck('created_at','total');

        $reqs = Requete::selectRaw("COUNT(*) number, DATE_FORMAT(created_at, '%Y %m %e') date")
                 ->groupBy('date')
                 ->pluck('number','date');

        $reqs = Requete::selectRaw("COUNT(*) number, DATE_FORMAT(created_at, '%Y %m %e') date, req_code as code")
                  ->groupBy('date','req_code')
                  ->get();
        return $reqs;
        return view('welcome',compact('pie_chart', 'line_chart', 'areaspline_chart', 'percentage_chart', 'geo_chart', 'area_chart', 'donut_chart'));
    }
}
