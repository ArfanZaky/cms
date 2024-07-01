<div class="form-group">
    <label for="menu_id">Target : </label>
    <select name="target_1[]" class="form-control input-sm show-tick" >
        <option {{ isset($data->translations[$i]->target_1) && $data->translations[$i]->target_1 == "_self" ? 'selected' : '' }} value="_self">_self</option>
        <option {{ isset($data->translations[$i]->target_1) && $data->translations[$i]->target_1 == "_blank" ? 'selected' : '' }} value="_blank">_blank</option>
    </select>
</div>
