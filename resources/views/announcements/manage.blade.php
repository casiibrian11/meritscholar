@extends('layouts.admin')

@section('content')
<input type="hidden" id="save-route" value="{{ route('announcements-save') }}" readonly>
<h3 class="mt-2 p-0"><i class="fa fa-bullhorn"></i> Announcements</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/announcements">Announcements</a></li>
</ol>
<div class="row">
    <div class="col-sm-12 p-0">
        <div class="card p-0 main-body">
            <div class="card-body px-5">
            <form action="" method="POST" id="form">
                <div class="modal-body">
                    <p class="alert alert-info small">
                        <strong><i class="fa fa-info-circle"></i> NOTE:</strong> Fields marked with <span class="required">*</span> are required.
                    </p>
                    @csrf
                    <input type="hidden" name="id" id="id" readonly>
                    <input type="hidden" name="content" id="content" value="{{ $data['announcement']['content'] ?? '' }}" readonly>
                    <input type="hidden" name="visible" id="visible" value="0" readonly>
                    <div class="form-group mb-2">
                        <label for="content">Content <span class="required">*</span></label>
                        <textarea class="form-control" placeholder="Detailed description of the requirement..." 
                            style="height:100px;" id="editor">{{ old('description', '') }}</textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_visible">
                        <label class="form-check-label">
                            Make this visible to the announcements page.
                        </label>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-primary save-announcement">Save</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

<script>
    CKEDITOR.replace('editor');

    $(function(){

        @if (!empty($data['announcement']))
            var content = $('#content').val();
            var visible = '{{ $data["announcement"]["visible"] }}';
            var isVisible = '{{ (boolean)$data["announcement"]["visible"] }}';
            CKEDITOR.instances['editor'].setData(content);
            $('#visible').val(visible);

            if (isVisible) {
                $('#is_visible').prop('checked', true);
            } else {
                $('#is_visible').prop('checked', false);
            }

            $('#id').val('{{ $data["announcement"]["id"] }}');
        @endif

        $('.save-announcement').on('click', function(){
            var editorText = CKEDITOR.instances.editor.getData();
            var isVisible = $('#is_visible').prop('checked');

            if (isVisible) {
                $('#visible').val(1);
            } else {
                $('#visible').val(0);
            }

            $('#content').val(editorText);
            $('#form').trigger('submit');
        });
    });
</script>
@endsection
