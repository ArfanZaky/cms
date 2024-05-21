<div class="col-12">
    <hr style="margin: 0;">
</div>
<div class="col-6" style="align-self: center;">
    <input id="thumbnail-sm" style="display: none" class="form-control" type="text" name="image_sm">
    <a id="lfm-sm" data-input="thumbnail-sm" data-preview="holder-sm">
        <div class="image_list" for="lfm-sm">
            <span>
                <i class="fa fa-upload"></i> Choose Image
            </span>
        </div>
    </a>

</div>
<div class="col-6">
    <div class="image_list_preview position-relative">
        <img src="{{ asset('assets/img/default.jpg') }}" id="holder-sm" width="400px" height="400px" alt="image"
        style="width: -webkit-fill-available;height: fit-content;" >
        <button type="button" class="close-button"
            onclick="DeleteImage('thumbnail-sm','holder-sm','{{asset('assets/img/default.jpg')}}' )">
        </button>
    </div>
</div>
