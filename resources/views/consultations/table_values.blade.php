<td>{{ $currval->phonenum }}</td>
<td>{{ $currval->type_demande->name }}</td>
<td>

@if ($currval->type_reponse->code == 1)
  <span class="badge badge-success">
@elseif($currval->type_reponse->code == 2)
  <span class="badge badge-light">
@elseif($currval->type_reponse->code == 3)
  <span class="badge badge-secondary">
@elseif($currval->type_reponse->code == -3 or $currval->type_reponse->code == -4 or $currval->type_reponse->code == -5 or $currval->type_reponse->code == -6)
  <span class="badge badge-warning">
@else
  <span class="badge badge-danger">
@endif
{{ $currval->type_reponse->name }}</span>
</td>

<td>{{ $currval->autorisation ? $currval->autorisation->date_debut : ' ' }}</td>
<td>{{ $currval->autorisation ? $currval->autorisation->date_fin : ' ' }}</td>
