<div class="form-group">
    <label for="menu_id">Target : </label>
    <select name="target[]" class="form-control input-sm show-tick" >
        <option {{ $data->translations[$i]->target == "_self" ? 'selected' : '' }} value="_self">_self</option>
        <option {{ $data->translations[$i]->target == "_blank" ? 'selected' : '' }} value="_blank">_blank</option>
    </select>
</div>