<?php 
($data->translations[$i]->image_md != 'default.jpg') ? $image_md_lang = env('APP_URL').$data->translations[$i]->image_md : $image_md_lang = asset('assets/img/default.jpg');
?>

<div class="col-12">
    <hr style="margin: 0;">
    <label for="image_lang">Image 3</label>

</div>
<div class="col-6" style="align-self: center;">
    <input id="thumbnail-md-{{$i}}" style="display: none" value="{{ $data->translations[$i]->image_md }}" class="form-control" type="text" name="image_md_lang[]">
    <a id="lfm-md-{{$i}}" data-input="thumbnail-md-{{$i}}" data-preview="holder-md-{{$i}}">
        <div class="image_list" for="lfm-md-{{$i}}">
            <span>
                <i class="fa fa-upload"></i> Choose Image
            </span>
        </div>
    </a>

</div>
<div class="col-6">
    <div class="image_list_preview position-relative">
        <img src="{{ $image_md_lang }}" id="holder-md-{{$i}}" width="400px" height="400px" alt="image"
        style="width: -webkit-fill-available;height: fit-content;" >
        <button type="button" class="close-button"
            onclick="DeleteImage('thumbnail-md-{{$i}}','holder-md-{{$i}}', '{{asset('assets/img/default.jpg')}}' )">
        </button>
    </div>
</div>
