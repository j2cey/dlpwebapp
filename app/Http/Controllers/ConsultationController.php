<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Requete;
use App\TypeDemande;
use App\TypeReponse;
use App\StatutAutorisation;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recherche_cols = ['id', 'nom', 'prenom', 'matricule', 'fonction'];

        $sortBy = 'id';
        $orderBy = 'asc';
        $perPage = 50;
        $seltypds = null;
        $statreqs = null;
        $dt_deb = null;
        $dt_fin = null;
        $dmeur = null;

        //if ($request->has('orderBy')) $orderBy = $request->query('orderBy');
        //if ($request->has('sortBy')) $sortBy = $request->query('sortBy');
        if ($request->has('perPage')) $perPage = $request->query('perPage');

        if ($request->has('seltypds')) $seltypds = $request->query('seltypds');
        if ($request->has('statreqs')) $statreqs = $request->query('statreqs');

        if ($request->has('dt_deb')) $dt_deb = $request->query('dt_deb');
        if ($request->has('dt_fin')) $dt_fin = $request->query('dt_fin');

        if ($request->has('dmeur')) $dmeur = $request->query('dmeur');

        //dd($request, $seltypds,$dt_deb,$dt_fin);
        $listvalues = Requete::search($dmeur,$statreqs,$seltypds,$dt_deb,$dt_fin)
              ->with('autorisation')->with('type_demande')->with('type_reponse')
              ->orderBy('id','desc')->paginate($perPage);

        if (is_null($seltypds)) {
          $seltypds = TypeDemande::where('name', 'x@gsf sgfscfs')->pluck('name', 'id');
        } else {
          $seltypds = TypeDemande::whereIn('id', $seltypds)->pluck('name', 'id');
        }

        if (is_null($statreqs)) {
          $statreqs = TypeReponse::where('name', 'x@gsf sgfscfs')->pluck('name', 'code');
        } else {
          $statreqs = TypeReponse::whereIn('code', $statreqs)->pluck('name', 'code');
        }

        //dd($employes);
        return view('consultations.index', compact('dmeur', 'seltypds', 'statreqs', 'dt_deb', 'dt_fin', 'listvalues', 'perPage'));
    }

    public function selectmoretypedemandes(Request $request)
    {
        $search = $request->get('search');
        $data = TypeDemande::select(['id', 'name'])
          ->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }

    public function selectmorestatutrequetes(Request $request)
    {
        $search = $request->get('search');
        $data = TypeReponse::select(['id', 'name'])
          ->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}
