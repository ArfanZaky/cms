<div class="col-12">
    <hr style="margin: 0;">
    <label for="image_lang">Image 6</label>

</div>
<div class="col-6" style="align-self: center;">
    <input id="thumbnail-mg-{{$i}}" style="display: none" class="form-control" type="text" name="image_mg_lang[]">
    <a id="lfm-mg-{{$i}}" data-input="thumbnail-mg-{{$i}}" data-preview="holder-mg-{{$i}}">
        <div class="image_list" for="lfm-mg-{{$i}}">
            <span>
                <i class="fa fa-upload"></i> Choose Image
            </span>
        </div>
    </a>

</div>
<div class="col-6">
    <div class="image_list_preview position-relative">
        <img src="{{ asset('assets/img/default.jpg') }}" id="holder-mg-{{$i}}" width="400px" height="400px" alt="image"
        style="width: -webkit-fill-available;height: fit-content;" >
        <button type="button" class="close-button"
            onclick="DeleteImage('thumbnail-mg-{{$i}}','holder-mg-{{$i}}', '{{asset('assets/img/default.jpg')}}' )">
        </button>
    </div>
</div>

