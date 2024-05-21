<div class="form-group">
    <label for="menu_id">Target : </label>
    <select name="target_2[]" class="form-control input-sm show-tick" >
        <option {{ $data->translations[$i]->target_2 == "_self" ? 'selected' : '' }} value="_self">_self</option>
        <option {{ $data->translations[$i]->target_2 == "_blank" ? 'selected' : '' }} value="_blank">_blank</option>
    </select>
</div>