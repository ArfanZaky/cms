   {{-- Status : --}}
   <div class="col-12">
    <div class="form-group">
        <label for="Status">Append Menu : </label>
        <select name="is_menu" id="select-picker" class="form-control input-sm show-tick select2" data-live-search="true" required>
            <option value="1" {{ $data->is_menu == 1 ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ $data->is_menu == 0 ? 'selected' : '' }}>No</option>
        </select>
    </div>
</div>
{{-- End Status --}}
