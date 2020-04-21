@extends('layouts.app')

@section('page')

@endsection

@section('css')
    @include('consultations.typedemandes_css')
@endsection

@section('content')

<div class="row">
  <div class="col-12">
    <div class="card m-b-30">
      <div class="card-body">
        <h4 class="mt-0 header-title">Consultation Demandes Autorisation</h4>
          <p class="text-muted m-b-30 font-14">Liste des <code class="highlighter-rouge">Requêtes</code> de demande d Autorisation.</p>

          <div class="row">
            @include('layouts.message')
          </div>

          <!-- Panel de recherche -->
          <div class="row">
            @include('layouts.recherche_panel_requetes', ['index_route' => 'ConsultationController@index'])
          </div>
          <!-- Fin Panel de recherche -->

          <div class="row">

            <table class="table table-hover table-sm">
              <thead class="thead-default">
                  <tr>
                      <th class="font-weight-bold">#</th>
                      @include('consultations.table_headers')
                      <th class="font-weight-bold">Date Creation</th>
                      <th class="font-weight-bold text-center" colspan="3">Actions</th>
                  </tr>
              </thead>
              <tbody>
                @forelse ($listvalues as $currval)
                  <tr>
                    <td class="font-weight-bold text-left">{{ $currval->id }}</td>
                    @include('consultations.table_values', ['currval' => $currval])
                    <td class="text-left">{{ date('d-m-Y H:i:s', strtotime($currval->created_at)) }}</td>

                    <!-- ACTIONS -->

                    <td style="width: 10px;">
                      <a href="#" alt="Détails" title="Details">
                        <i class="fa fa-eye" style="color:green"></i>
                      </a>
                    </td>

                  </tr>
                  @empty
                @endforelse
                <input type="hidden" name="user" id="user" value="">
              </tbody>
            </table>

            {{ $listvalues->appends(request()->input())->links() }}

          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('js')
    @include('consultations.typedemandes_js')
    @include('consultations.statutautorisations_js')
@endsection
