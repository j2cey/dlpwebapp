<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Requete;
use App\TypeDemande;
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
        $statauts = null;
        $dt_deb = null;
        $dt_fin = null;
        $dmeur = null;

        //if ($request->has('orderBy')) $orderBy = $request->query('orderBy');
        //if ($request->has('sortBy')) $sortBy = $request->query('sortBy');
        if ($request->has('perPage')) $perPage = $request->query('perPage');

        if ($request->has('seltypds')) $seltypds = $request->query('seltypds');
        if ($request->has('statauts')) $statauts = $request->query('statauts');

        if ($request->has('dt_deb')) $dt_deb = $request->query('dt_deb');
        if ($request->has('dt_fin')) $dt_fin = $request->query('dt_fin');

        if ($request->has('dmeur')) $dmeur = $request->query('dmeur');

        //dd($request, $seltypds,$dt_deb,$dt_fin);
        $listvalues = Requete::search($dmeur,$seltypds,$dt_deb,$dt_fin)->orderBy('id')->paginate($perPage);

        if (is_null($seltypds)) {
          $seltypds = TypeDemande::where('name', 'x@gsf sgfscfs')->pluck('name', 'id');
        } else {
          $seltypds = TypeDemande::whereIn('id', $seltypds)->pluck('name', 'id');
        }

        if (is_null($statauts)) {
          $statauts = StatutAutorisation::where('name', 'x@gsf sgfscfs')->pluck('name', 'code');
        } else {
          $statauts = StatutAutorisation::whereIn('code', $statauts)->pluck('name', 'code');
        }

        //dd($employes);
        return view('consultations.index', compact('dmeur', 'seltypds', 'statauts', 'dt_deb', 'dt_fin', 'listvalues', 'perPage'));
    }

    public function selectmoretypedemandes(Request $request)
    {
        $search = $request->get('search');
        $data = TypeDemande::select(['id', 'name'])
          ->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }

    public function selectmorestatutautorisations(Request $request)
    {
        $search = $request->get('search');
        $data = StatutAutorisation::select(['id', 'name'])
          ->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}
