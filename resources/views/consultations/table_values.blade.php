<td>{{ $currval->phonenum }}</td>
<td>{{ $currval->type_demande->name }}</td>
<td>{{ $currval->type_reponse->name }}</td>
<td>{{ $currval->autorisation ? $currval->autorisation->date_debut : ' ' }}</td>
<td>{{ $currval->autorisation ? $currval->autorisation->date_fin : ' ' }}</td>
 <td>
 @if (isset($currval->autorisation))
  @if($currval->autorisation->is_active)
    <span class="badge badge-primary">active</span>
  @else
    <span class="badge badge-danger">echue</span>
  @endif
 @else
    <span class="badge badge-warning">aucune</span>
 @endif
 </td>
