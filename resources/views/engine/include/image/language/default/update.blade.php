<?php 
($data->translations[$i]->image_lang != 'default.jpg') ? $image_lang = env('APP_URL').$data->translations[$i]->image_lang : $image_lang = asset('assets/img/default.jpg');
?>

<div class="col-12">
    <hr style="margin: 0;">
    <label for="image_lang">Image</label>
</div>
<div class="col-6" style="align-self: center;">
    <input id="thumbnail-{{$i}}" style="display: none" value="{{ $data->translations[$i]->image_lang }}"  class="form-control" type="text" name="image_lang[]">
    <a id="lfm-{{$i}}" data-input="thumbnail-{{$i}}" data-preview="holder-{{$i}}">
        <div class="image_list" for="lfm-{{$i}}">
            <span>
                <i class="fa fa-upload"></i> Choose Image
            </span>
        </div>
    </a>

</div>
<div class="col-6">
    <div class="image_list_preview position-relative">
        <img src="{{ $image_lang }}" id="holder-{{$i}}" width="400px" height="400px" alt="image"
        style="width: -webkit-fill-available;height: fit-content;" >
        <button type="button" class="close-button"
            onclick="DeleteImage('thumbnail-{{$i}}','holder-{{$i}}','{{asset('assets/img/default.jpg')}}' )">
        </button>
    </div>
</div>
