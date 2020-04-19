@if (session()->has('msg_success'))
    <div class="alert alert alert-success">
        {{ session()->get('msg_success') }}
    </div>
@endif
@if (session()->has('msg_primary'))
    <div class="col alert alert-primary">
        {{ session()->get('msg_primary') }}
    </div>
@endif
@if (session()->has('msg_danger'))
    <div class="col alert alert-danger">
        {{ session()->get('msg_danger') }}
    </div>
@endif
