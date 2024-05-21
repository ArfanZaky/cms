 {{-- Publish Date : --}}
 <div class="col-12">
    <div class="form-group">
        <label for="publish_at">Publish Date : <em class="text-danger">*required</em></label>
        <input type="date" name="publish_at" id="publish_at" class="form-control"  value="{{ Date('Y-m-d') }}" 
        required>
    </div>
</div>
{{-- End Publish Date --}}
{{-- Unpublish Date : --}}
<div class="col-12">
    <div class="form-group">
        <label for="unpublish_at">Unpublish Date : <em class="text-danger">*required</em></label>
        <input type="date" name="unpublish_at" id="unpublish_at" class="form-control" required value="{{ old('unpublish_at') }}">
    </div>
</div>