

<div class="card-header flex flex-wrap justify-content-between">
    <h4>Section</h4>
    <button type="button" class="btn btn-primary section-button-{{$i}}" >
        <div class="text-center">
            <i class="fas fa-plus"></i>
            Add Section
        </div>
    </button>
</div>
<div class="card-body">
    <div class="tab-content section-content-{{$i}}" id="myTabContent2">
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function () {
        let index = 1;
        let i = "{{$i}}";
        $('.section-button-'+i).click(function () {
            index++;
            $.ajax({
                url: "{{ route('addSection') }}",
                type: "POST",
                data: {
                    lang: i,
                    type: 'add',
                    _token: "{{ csrf_token() }}",
                    index: index,
                },
                success: function(data) {
                    $('.section-content-'+i).append(data);
                    editor_config_builder('.tinymce_plugins'+i+index);
                },
                error: function(data) {
                    console.log(data);
                }
            });

        });
    });
</script>

@endpush
