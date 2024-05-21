<div class="col-12">
    <hr style="margin: 0;">
</div>
<div class="col-6" style="align-self: center;">
    <input id="thumbnail-md" style="display: none" class="form-control" type="text" name="image_md">
    <a id="lfm-md" data-input="thumbnail-md" data-preview="holder-md">
        <div class="image_list" for="lfm-md">
            <span>
                <i class="fa fa-upload"></i> Choose Image
            </span>
        </div>
    </a>

</div>
<div class="col-6">
    <div class="image_list_preview position-relative">
        <img src="{{ asset('assets/img/default.jpg') }}" id="holder-md" width="400px" height="400px" alt="image"
        style="width: -webkit-fill-available;height: fit-content;" >
        <button type="button" class="close-button"
            onclick="DeleteImage('thumbnail-md','holder-md', '{{asset('assets/img/default.jpg')}}' )">
        </button>
    </div>
</div>
