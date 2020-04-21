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
        <div class="card card-sec m-b-30">
            <div class="card-body">
                <h4 class="mt-0 m-b-15 header-title">Top Demandes Hebdo</h4>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="titles">
                                <th>Costumer Name</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Invoice</th>
                                <th>Start date</th>
                                <th>Amount</th>
                            </tr>

                        </thead>
                        <tbody>
                            <tr>
                                <td class="c-table__cell">
                                    <div class="user-wrapper">
                                        <div class="img-user">
                                            <img src="assets/images/users/user-1.jpg" alt="user" class="rounded-circle">
                                        </div>
                                        <div class="text-user">
                                            <h6>Tiger Nixon</h6>
                                            <p>Web Designer</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="c-table__cell">Dribble</td>
                                <td class="c-table__cell"><span class="badge badge-warning">Due</span></td>
                                <td class="c-table__cell">INV-001001</td>
                                <td class="c-table__cell">2011/04/25</td>
                                <td class="c-table__cell">$320,800</td>
                            </tr>
                            <tr>
                                <td class="c-table__cell">
                                    <div class="user-wrapper">
                                        <div class="img-user">
                                            <img src="assets/images/users/user-2.jpg" alt="user" class="rounded-circle">
                                        </div>
                                        <div class="text-user">
                                            <h6>Tiger Nixon</h6>
                                            <p>Web Designer</p>
                                        </div>
                                    </div>
                                </td>
                                <td>Accountant</td>
                                <td><span class="badge badge-info">Paid</span></td>
                                <td>63</td>
                                <td>2011/07/25</td>
                                <td>$170,750</td>
                            </tr>
                            <tr>
                                <td class="c-table__cell">
                                    <div class="user-wrapper">
                                        <div class="img-user">
                                            <img src="assets/images/users/user-3.jpg" alt="user" class="rounded-circle">
                                        </div>
                                        <div class="text-user">
                                            <h6>Tiger Nixon</h6>
                                            <p>Web Designer</p>
                                        </div>
                                    </div>
                                </td>
                                <td>Junior Technical Author</td>
                                <td><span class="badge badge-info">Paid</span></td>
                                <td>66</td>
                                <td>2009/01/12</td>
                                <td>$86,000</td>
                            </tr>
                            <tr>
                                <td class="c-table__cell">
                                    <div class="user-wrapper">
                                        <div class="img-user">
                                            <img src="assets/images/users/user-4.jpg" alt="user" class="rounded-circle">
                                        </div>
                                        <div class="text-user">
                                            <h6>Tiger Nixon</h6>
                                            <p>Web Designer</p>
                                        </div>
                                    </div>
                                </td>

                                <td>Senior Javascript Developer</td>
                                <td><span class="badge badge-warning">Due</span></td>
                                <td>22</td>
                                <td>2012/03/29</td>
                                <td>$433,060</td>
                            </tr>
                        </tbody>
                    </table>
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
