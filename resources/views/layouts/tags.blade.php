<div class="form-group row {{ $errors->has('tags') ? 'has-error' : '' }}">
    <label class="col-sm-2 col-form-label"for="tags">Tags</label>
    <div class="col-sm-10">
      <select id="tags" multiple="multiple" style="width:100%">
        <option value="Systeme" selected="selected">Systeme</option>
        <option value="Type Article" selected="selected">Type Article</option>
      </select>
      <small class="text-danger">{{ $errors->has('tags') ? $errors->first('tags') : '' }}</small>
    </div>
</div>
