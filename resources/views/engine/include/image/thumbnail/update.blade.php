<?php 
($data->image_lg != 'default.jpg') ? $image_lg = env('APP_URL').$data->image_lg : $image_lg = asset('assets/img/default.jpg');
?>
<div class="col-12">
    <hr style="margin: 0;">
</div>
<div class="col-6" style="align-self: center;">
    <input id="thumbnail-lg" style="display: none"  value="{{ $data->image_lg }}"  class="form-control" type="text" name="image_lg">
    <a id="lfm-lg" data-input="thumbnail-lg" data-preview="holder-lg">
        <div class="image_list">
            <span>
                <i class="fa fa-upload"></i> Choose Image
            </span>
        </div>
    </a>
</div>
<div class="col-6">
    <div class="image_list_preview position-relative">
        <img src="{{ $image_lg }}" id="holder-lg" width="400px" height="400px" alt="image"
        style="width: -webkit-fill-available;height: fit-content;" >
        <button type="button" class="close-button"
            onclick="DeleteImage('thumbnail-lg','holder-lg', '{{asset('assets/img/default.jpg')}}' )">
        </button>
    </div>
</div>
