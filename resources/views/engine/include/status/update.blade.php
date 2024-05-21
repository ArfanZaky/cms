   {{-- Status : --}}
   <div class="col-12">
    <div class="form-group">
        <label for="Status">Status : </label>
        <select name="status" id="select-picker" class="form-control input-sm show-tick select2" data-live-search="true" required>
            <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
</div>
{{-- End Status --}}