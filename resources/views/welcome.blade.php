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
              <span class="mini-stat-icon"><i class="ti-package"></i></span>
              <div class="mini-stat-info text-right text-light">
                  <span class="counter text-white">XXXX</span> Total Articles
              </div>
          </div>
      </div>
      <div class="col-md-6 col-xl-3">
          <div class="mini-stat clearfix bg-orange">
              <span class="mini-stat-icon"><i class="ti-server"></i></span>
              <div class="mini-stat-info text-right text-light">
                  <span class="counter text-white">XXXX</span> Total Articles en Stock
              </div>
          </div>
      </div>
      <div class="col-md-6 col-xl-3">
          <div class="mini-stat clearfix bg-info">
              <span class="mini-stat-icon"><i class="ti-user"></i></span>
              <div class="mini-stat-info text-right text-light">
                  <span class="counter text-white">XXXX</span> Total Articles en Affectation
              </div>
          </div>
      </div>
      <div class="col-md-6 col-xl-3">
          <div class="mini-stat clearfix bg-success">
              <span class="mini-stat-icon"><i class="ti-harddrives"></i></span>
              <div class="mini-stat-info text-right text-light">
                  <span class="counter text-white">XXXX</span> Total Types
              </div>
          </div>
      </div>
</div>

<div class="row">
  <div class="col-xl-12">
      <div class="card card-sec m-b-12">
          <div class="card-body">
              <h4 class="mt-12 m-b-12 header-title">Détails Types Article</h4>

              {!! $chart->container() !!}

              {!! $chart->script() !!}

          </div>
      </div>
    </div>

  </div>
@endsection
