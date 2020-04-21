@extends('layouts.app')

@section('page')
  Bienvenue
@endsection

@section('css')
<style type="text/css">
    body{
      /*background-color: #bdc3c7;*/
      }

    thead, tbody { display: block; }

    tbody {
      height: 340px;       /* Just for the demo          */
      overflow-y: auto;    /* Trigger vertical scroll    */
      overflow-x: hidden;  /* Hide the horizontal scroll */
    }
</style>

@endsection

@section('content')
<div class="row">
      <div class="col-md-6 col-xl-3">
          <div class="mini-stat clearfix bg-white">
              <span class="mini-stat-icon"><i class="ti-harddrives"></i></span>
              <div class="mini-stat-info text-right text-light">
                  <span class="counter text-white">{{ $totalconsultation_dujour }}</span>Consultations du Jour
              </div>
          </div>
      </div>
      <div class="col-md-6 col-xl-3">
          <div class="mini-stat clearfix bg-orange">
              <span class="mini-stat-icon"><i class="ti-shopping-cart-full"></i></span>
              <div class="mini-stat-info text-right text-light">
                  <span class="counter text-white">{{ $totaldemandesalimentaire_dujour }}</span>Demandes Alimentaires du Jour
              </div>
          </div>
      </div>
      <div class="col-md-6 col-xl-3">
          <div class="mini-stat clearfix bg-info">
              <span class="mini-stat-icon"><i class="ti-heart-broken"></i></span>
              <div class="mini-stat-info text-right text-light">
                  <span class="counter text-white">{{ $totaldemandessante_dujour }}</span>Demandes Santé du Jour
              </div>
          </div>
      </div>
      <div class="col-md-6 col-xl-3">
          <div class="mini-stat clearfix bg-success">
              <span class="mini-stat-icon"><i class="ti-alert"></i></span>
              <div class="mini-stat-info text-right text-light">
                  <span class="counter text-white">{{ $totaldemandesurgences_dujour }}</span>Demandes Urgence du Jour
              </div>
          </div>
      </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-sec m-b-30">
            <div class="card-body">
                <h4 class="mt-0 header-title">Requêtes & Consultations de la Journée</h4>
                <div class="container">
                  {!! $requetesetconsultations_dujour_chart->container() !!}
                  {!! $requetesetconsultations_dujour_chart->script() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-sec m-b-30">
            <div class="card-body">
                <h4 class="mt-0 header-title">Autorisations de la Journée</h4>

                <div class="container">
                  {!! $autorisations_dujour_chart->container() !!}
                  {!! $autorisations_dujour_chart->script() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-xl-8">



      <div class="card-group">
        <div class="card">
          <div class="card-body">
            <h6 class="mt-0 m-b-15 header-title">Top Hebdo Demandes Sorties Alimentaire</h6>

                <div class="table-responsive">
                  <table class="table table-hover mb-12">
                    <thead>
                    </thead>
                    <tbody>
                      @forelse ($tophebdodemandeurs_alim as $demandeurs)
                            <tr>
                              <td class="c-table__cell">{{ $demandeurs->demandeur }}</td>

                              <td class="c-table__cell">
                                @if($demandeurs->count <= 5)
                                  <span class="badge badge-success">{{ $demandeurs->count }}</span>
                                @else
                                  <span class="badge badge-danger">{{ $demandeurs->count }}</span>
                                @endif
                              </td>
                        @empty
                        @endforelse
                    </tbody>
                  </table>
            </div>

            <p class="card-text"><small class="text-muted">Soit {{ $tophebdodemandeurs_alim->count() }} demandeur(s).</small></p>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <h6 class="mt-0 m-b-15 header-title">Top Hebdo Demandes Sorties de Santé</h6>

              <div class="table-responsive">
                <table class="table table-hover mb-12">
                  <thead>
                  </thead>
                  <tbody>
                    @forelse ($tophebdodemandeurs_sante as $demandeurs)
                          <tr>
                            <td class="c-table__cell">{{ $demandeurs->demandeur }}</td>

                            <td class="c-table__cell">
                              @if($demandeurs->count <= 5)
                                <span class="badge badge-success">{{ $demandeurs->count }}</span>
                              @else
                                <span class="badge badge-danger">{{ $demandeurs->count }}</span>
                              @endif
                            </td>
                      @empty
                      @endforelse
                  </tbody>
                </table>
              </div>

            <p class="card-text"><small class="text-muted">Soit {{ $tophebdodemandeurs_sante->count() }} demandeur(s).</small></p>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <h6 class="mt-0 m-b-15 header-title">Top Hebdo Demandes Sorties Urgence</h6>

            <div class="table-responsive">
              <table class="table table-hover mb-12">
                <thead>
                </thead>
                <tbody>
                  @forelse ($tophebdodemandeurs_urg as $demandeurs)
                        <tr>
                          <td class="c-table__cell">{{ $demandeurs->demandeur }}</td>

                          <td class="c-table__cell">
                            @if($demandeurs->count <= 5)
                              <span class="badge badge-success">{{ $demandeurs->count }}</span>
                            @else
                              <span class="badge badge-danger">{{ $demandeurs->count }}</span>
                            @endif
                          </td>
                    @empty
                    @endforelse
                </tbody>
              </table>
            </div>

            <p class="card-text"><small class="text-muted">Soit {{ $tophebdodemandeurs_urg->count() }} demandeur(s).</small></p>
          </div>
        </div>
      </div>







    </div>

    <div class="col-xl-4">
        <div class="card card-sec m-b-30">
            <div class="card-body">
                <h4 class="mt-0 header-title">Autorisations de la Semaine</h4>

                <div class="container">
                  {!! $autorisations_hebdo_chart->container() !!}
                  {!! $autorisations_hebdo_chart->script() !!}
                </div>
            </div>
        </div>
    </div>

</div>
<!-- end row -->
@endsection
