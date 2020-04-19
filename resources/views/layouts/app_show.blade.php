@extends('layouts.app')

@section('page')
  @include('layouts._button_index', ['canlist' => $canlist, 'index_route' => $index_route, 'model' => $model, 'title' => $title])
@endsection

@section('buttons')
  @if($model->isDeleted($model))
    @include('layouts._button_trash_restore', ['canrestoretrash' => $canrestoretrash, 'recycle_bin_id' => $breadcrumb_param])
    @include('layouts._button_trash_delete', ['candeletetrash' => $candeletetrash, 'recycle_bin_id' => $breadcrumb_param])
  @else
    @include('layouts._button_edit', ['canedit' => $canedit, 'edit_route' => $edit_route, 'model' => $model])
    @include('layouts._button_delete', ['candelete' => $candelete, 'destroy_route' => $destroy_route, 'model' => $model])
  @endif
@endsection

@section('breadcrumb')
  {{ Breadcrumbs::render($breadcrumb_title,$breadcrumb_param) }}
@endsection

@section('content')

<div class="row">
  @include('layouts.message')
</div>

  <div class="row">
    <div class="col-12">
      <div class="card m-b-30">
        <div class="card-body">
          <h4 class="mt-0 header-title">Détails</h4>
            <p class="text-muted m-b-30 font-14">Détails {{ $modeltype }} <code class="highlighter-rouge"><strong>{{ ucfirst($modelname) }}</strong></code>.</p>

            @yield('show_details')

        </div>
      </div>
    </div>
  </div>
@endsection
