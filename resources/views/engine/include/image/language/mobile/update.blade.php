<?php 
($data->translations[$i]->image_sm_lang != 'default.jpg') ? $image_sm_lang = env('APP_URL').$data->translations[$i]->image_sm_lang : $image_sm_lang = asset('assets/img/default.jpg');
?>
<div class="col-12">
    <hr style="margin: 0;">
    <label for="image_lang">Image Mobile</label>
</div>
<div class="col-6" style="align-self: center;">
    <input id="thumbnail-sm-{{$i}}" style="display: none" value="{{ $data->translations[$i]->image_sm_lang }}" class="form-control" type="text" name="image_sm_lang[]">
    <a id="lfm-sm-{{$i}}" data-input="thumbnail-sm-{{$i}}" data-preview="holder-sm-{{$i}}">
        <div class="image_list" for="lfm-sm-{{$i}}">
            <span>
                <i class="fa fa-upload"></i> Choose Image
            </span>
        </div>
    </a>

</div>
<div class="col-6">
    <div class="image_list_preview position-relative">
        <img src="{{ $image_sm_lang }}" id="holder-sm-{{$i}}" width="400px" height="400px" alt="image"
        style="width: -webkit-fill-available;height: fit-content;" >
        <button type="button" class="close-button"
            onclick="DeleteImage('thumbnail-sm-{{$i}}','holder-sm-{{$i}}','{{asset('assets/img/default.jpg')}}' )">
        </button>
    </div>
</div>
