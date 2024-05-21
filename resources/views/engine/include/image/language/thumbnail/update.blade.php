<?php 
($data->translations[$i]->image_lg != 'default.jpg') ? $image_lg_lang = env('APP_URL').$data->translations[$i]->image_lg : $image_lg_lang = asset('assets/img/default.jpg');
?>

<div class="col-12">
    <hr style="margin: 0;">
    <label for="image_lang">Image 4</label>

</div>
<div class="col-6" style="align-self: center;">
    <input id="thumbnail-lg-{{$i}}" style="display: none" value="{{ $data->translations[$i]->image_lg }}" class="form-control" type="text" name="image_lg_lang[]">
    <a id="lfm-lg-{{$i}}" data-input="thumbnail-lg-{{$i}}" data-preview="holder-lg-{{$i}}">
        <div class="image_list" for="lfm-lg-{{$i}}">
            <span>
                <i class="fa fa-upload"></i> Choose Image
            </span>
        </div>
    </a>

</div>
<div class="col-6">
    <div class="image_list_preview position-relative">
        <img src="{{ $image_lg_lang }}" id="holder-lg-{{$i}}" width="400px" height="400px" alt="image"
        style="width: -webkit-fill-available;height: fit-content;" >
        <button type="button" class="close-button"
            onclick="DeleteImage('thumbnail-lg-{{$i}}','holder-lg-{{$i}}', '{{asset('assets/img/default.jpg')}}' )">
        </button>
    </div>
</div>


