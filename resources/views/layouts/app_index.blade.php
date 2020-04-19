@extends('layouts.app')

@section('page')

@endsection

@section('buttons')
  @if($from_index_create)
    @include('layouts._button_create', ['cancreate' => $cancreate, 'create_route' => $create_route])
  @endif
@endsection

@section('breadcrumb')
  {{ Breadcrumbs::render($breadcrumb_title,$breadcrumb_param) }}
@endsection

@section('content')

<div class="row">
  <div class="col-12">
    <div class="card m-b-30">
      <div class="card-body">
        <h4 class="mt-0 header-title">{{ ucfirst($title) }}</h4>
          <p class="text-muted m-b-30 font-14">Liste de tous(tes) les <code class="highlighter-rouge">{{ ucfirst($title) }}</code> du Système.</p>

          <div class="row">
            @include('layouts.message')
          </div>

          <!-- Panel de recherche -->
          <div class="row">
            @include('layouts.recherche_panel', ['index_route'=>$index_route])
          </div>
          <!-- Fin Panel de recherche -->

          <div class="row">

            <table class="table table-hover table-sm">
              <thead class="thead-default">
                  <tr>
                      <th class="font-weight-bold">#</th>
                      @include($table_headers)
                      <th class="font-weight-bold">Date Creation</th>
                      <th class="font-weight-bold">Statut</th>
                      @if($affectation_tag == '')
                        <th class="font-weight-bold text-center" colspan="3">Actions</th>
                      @else
                        <th class="font-weight-bold text-center" colspan="4">Actions</th>
                      @endif
                  </tr>
              </thead>
              <tbody>
                @forelse ($listvalues as $currval)
                  <tr>
                    <td class="font-weight-bold text-left">{{ $currval->id }}</td>
                    @include($table_values, ['currval' => $currval])
                    <td class="text-left">{{ date('d-m-Y H:i:s', strtotime($currval->created_at)) }}</td>

                    <td class="text-left">
                        <input {{ Auth::user()->can($canchangestatut) ? '' : 'disabled' }} data-id="{{$currval->id}}" data-tablename="{{$table_name}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Actif" data-off="Inactif" data-size="xs" {{ $currval->statut->code == 'actif' ? 'checked' : '' }}>
                    </td>

                    <!-- ACTIONS -->

                    <td style="width: 10px;">
                      <a href="{{ action($show_route, $currval) }}" alt="Détails" title="Details">
                        <i class="fa fa-eye" style="color:green"></i>
                      </a>
                    </td>

                    <td style="width: 10px;">
                      @can($canedit)
                        <a href="{{ action($edit_route, $currval) }}" alt="Modifer" title="Edit">
                          <i class="ti-pencil-alt"></i>
                        </a>
                      @endcan
                    </td>

                    @if($affectation_tag == '')
                    @else
                      <td style="width: 10px;">
                        @can($cancreateaffectation)
                          <a href="{{ action('AffectationController@elemcreate', [$affectation_tag, $currval->id]) }}" alt="Affecter Articles" title="Affecter Articles">
                            <i class="fa fa-paperclip"  style="color:green"></i>
                          </a>
                        @endcan
                      </td>
                    @endif

                    <td style="width: 10px;">
                      @can($candelete)
                        <a href="#" onclick="if(confirm('Etes-vous sur de vouloir supprimer?')) {event.preventDefault(); document.getElementById('index_destroy-form-{{ $currval->id }}').submit();}">
                          <i class="ti-trash" style="color:red"></i>
                        </a>
                        <form id="index_destroy-form-{{ $currval->id }}" action="{{ action($destroy_route, $currval->id) }}" method="POST" style="display: none;">
                          @method('DELETE')
                          @csrf
                          <input type="hidden" value="{{ $currval->id }}" name="id">
                        </form>
                      @endcan
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
    @include('statuts.change_js')
@endsection
