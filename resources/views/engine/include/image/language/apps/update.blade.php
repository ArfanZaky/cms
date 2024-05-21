<?php 
($data->translations[$i]->image_xs != 'default.jpg') ? $image_xs_lang = env('APP_URL').$data->translations[$i]->image_xs : $image_xs_lang = asset('assets/img/default.jpg');
?>



<div class="col-12">
    <hr style="margin: 0;">
    <label for="image_lang">Image 5</label>

</div>
<div class="col-6" style="align-self: center;">
    <input id="thumbnail-xs-{{$i}}" style="display: none"  value="{{ $data->translations[$i]->image_xs }}" class="form-control" type="text" name="image_xs_lang[]">
    <a id="lfm-xs-{{$i}}" data-input="thumbnail-xs-{{$i}}" data-preview="holder-xs-{{$i}}">
        <div class="image_list" >
            <span>
                <i class="fa fa-upload"></i> Choose Image
            </span>
        </div>
    </a>
</div>
<div class="col-6">
    <div class="image_list_preview position-relative">
        <img src="{{ $image_xs_lang }}" id="holder-xs-{{$i}}" width="400px" height="400px" alt="image"
        style="width: -webkit-fill-available;height: fit-content;" >
        <button type="button" class="close-button"
            onclick="DeleteImage('thumbnail-xs-{{$i}}','holder-xs-{{$i}}','{{asset('assets/img/default.jpg')}}' )">
        </button>
    </div>
</div>


