@extends('layouts.app')

@section('page')
  @include('layouts._button_index', ['canlist' => $canlist, 'index_route' => $index_route, 'model' => $model, 'title' => $title])
@endsection

@section('buttons')
  <!-- @include('layouts._button_delete', ['candelete' => $candelete, 'destroy_route' => $destroy_route, 'model' => $model]) -->
@endsection

@section('breadcrumb')
  {{ Breadcrumbs::render($breadcrumb_title,$breadcrumb_param) }}
@endsection

@section('css')
    @yield('more_css')
@endsection

@section('content')

<div class="row">
  <div class="col-12">
    <div class="card m-b-30">
      <div class="card-body">
        <h4 class="mt-0 header-title">Modification</h4>
          <p class="text-muted m-b-30 font-14">Modification {{ $modeltype }} <code class="highlighter-rouge"><strong>{{ ucfirst($modelname) }}</strong></code>.</p>

          <form action="{{ action($update_route, $model) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include($model_fields)
            @foreach($morecontrols as $control)
              @include($control)
            @endforeach

            <div class="form-group row">
                <div>
                  @can($canedit)
                  <button type="submit" class="btn btn-primary waves-effect waves-light">Valider</button>
                  @endcan
                  <button type="reset" class="btn btn-success waves-effect waves-light">Reset</button>
                  <a href="{{ action($index_route) }}" class="btn btn-secondary waves-effect m-l-5">Annuler</a>
                </div>
            </div>

          </form>

          @if(isset($moreforms))
            @foreach($moreforms as $form)
              @include($form)
            @endforeach
          @endif

        </div>
    </div>
  </div>
</div>

@endsection

@section('js')
    @yield('more_js')
@endsection
