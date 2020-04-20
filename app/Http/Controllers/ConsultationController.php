<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Requete;
use App\TypeDemande;

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
        $perPage = 5;
        $type_demande_id = null;
        $dt_deb = null;
        $dt_fin = null;

        if ($request->has('orderBy')) $orderBy = $request->query('orderBy');
        if ($request->has('sortBy')) $sortBy = $request->query('sortBy');
        if ($request->has('perPage')) $perPage = $request->query('perPage');

        if ($request->has('type_demande_id')) $type_demande_id = $request->query('type_demande_id');
        if ($request->has('dt_deb')) $dt_deb = $request->query('dt_deb');
        if ($request->has('dt_fin')) $dt_fin = $request->query('dt_fin');

        //dd($type_demande_id,$dt_deb,$dt_fin);

        $listvalues = Requete::search($type_demande_id,$dt_deb,$dt_fin)->orderBy($sortBy, $orderBy)->paginate($perPage);

        $type_demandes = TypeDemande::pluck('name', 'id');

        //dd($employes);
        return view('consultations.index', compact('type_demandes', 'listvalues', 'recherche_cols', 'orderBy', 'sortBy', 'q', 'perPage'));
    }
}
