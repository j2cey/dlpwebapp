<form action="{{ action($index_route) }}">
  <div class="row">

    <div class="col-md-2 col-3">
      <select name="sortBy" class="form-control form-control-sm" value="{{ $sortBy }}">
        @foreach($recherche_cols as $key => $col)
          <option @if($col == $sortBy) selected @endif value="{{ $col }}">{{ ucfirst($col) }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-2 col-3">
      <select name="orderBy" class="form-control form-control-sm" value="{{ $orderBy }}">
        @foreach(['asc', 'desc'] as $order)
          <option @if($order == $orderBy) selected @endif value="{{ $order }}">{{ ucfirst($order) }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-2 col-3">
      <select name="perPage" class="form-control form-control-sm" value="{{ $perPage }}">
        @foreach(['5','10','20','50','100','250'] as $page)
          <option @if($page == $perPage) selected @endif value="{{ $page }}">{{ $page }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-4 col-3">
      <div class="input-group form-inline mb-4">

        <div class="input-daterange input-group" id="date-range">

            <select name="type_demande_id" class="type_demande_id form-control" id="type_demande_id" style="min-width:30%;">
              <option value="-1">Type Demande</option>
              @foreach($type_demandes as $id => $display)
                  <option value="{{ $id }}">{{ $display }}</option>
              @endforeach
            </select>

            <input name="dt_deb" type="text" class="form-control" name="start" placeholder="Début" />
            <input name="dt_fin" type="text" class="form-control" name="end" placeholder="Fin" />

            
            <span class="input-group-append">
              <button type="submit" class="btn btn-outline-secondary"><i class="ti-search"></i></button>
            </span>

        </div>
      </div>
    </div>

  </div>
</form>
