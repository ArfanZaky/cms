<?php 
($data->image != 'default.jpg') ? $image = env('APP_URL').$data->image : $image = asset('assets/img/default.jpg');
?>
<div class="col-12">
    <hr style="margin: 0;">
</div>
<div class="col-6" style="align-self: center;">
    <input id="thumbnail" style="display: none" value="{{ $data->image }}"  class="form-control" type="text" name="image">
    <a id="lfm" data-input="thumbnail" data-preview="holder">
        <div class="image_list">
            <span>
                <i class="fa fa-upload"></i> Choose Image
            </span>
        </div>
    </a>

</div>
<div class="col-6">
    <div class="image_list_preview position-relative">
        <img src="{{ $image }}" id="holder" width="400px" height="400px" alt="image" style="width: -webkit-fill-available;height: fit-content;" />
        <button type="button" class="close-button"
            onclick="DeleteImage('thumbnail','holder','{{asset('assets/img/default.jpg')}}' )">
        </button>
    </div>
</div>
