<div class="col-12">
    <hr style="margin: 0;">
    <label for="image_lang">Image Mobile</label>
</div>
<div class="col-6" style="align-self: center;">
    <input id="thumbnail-sm-{{$i}}" style="display: none" class="form-control" type="text" name="image_sm_lang[]">
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
        <img src="{{ asset('assets/img/default.jpg') }}" id="holder-sm-{{$i}}" width="400px" height="400px" alt="image"
        style="width: -webkit-fill-available;height: fit-content;" >
        <button type="button" class="close-button"
            onclick="DeleteImage('thumbnail-sm-{{$i}}','holder-sm-{{$i}}','{{asset('assets/img/default.jpg')}}' )">
        </button>
    </div>
</div>