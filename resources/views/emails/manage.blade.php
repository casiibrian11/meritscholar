@extends('layouts.admin')

@section('content')
<input type="hidden" id="save-route" value="{{ route('announcements-save') }}" readonly>
<h3 class="mt-2 p-0"><i class="fa fa-envelope"></i> Email Templates</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item active"><a href="/emails/templates">Email Templates</a></li>
</ol>
<div class="row">
    <div class="col-sm-12 p-0">
        <div class="card p-0 main-body">
            <div class="card-header text-right">
                <a href="/emails/templates?template={{ $data['template'] }}" class="btn btn-xs btn-success add-new p-1 px-3">
                    <i class="fa fa-plus"></i> CREATE NEW
                </a>
            </div>
            <div class="card-body">
            <form action="" method="POST" id="form">
                <div class="modal-body">
                    <p class="alert alert-primary small">
                        <i class="fa fa-info-circle"></i> This email notification template will be sent to the user when their application is <b>{{ $data['statuses'][$data['template']] }}</b>
                    </p>
                    <p class="alert alert-info small">
                        <strong><i class="fa fa-info-circle"></i> NOTE:</strong> Fields marked with <span class="required">*</span> are required.
                    </p>
                    @csrf
                    <input type="hidden" name="id" id="id" readonly>
                    <input type="hidden" name="email_content" id="email_content" readonly>
                    <div class="form-group mb-2">
                        <label for="content">Content <span class="required">*</span></label>
                        <textarea class="form-control" placeholder="Content to post as announcement..." 
                            style="height:100px;" id="editor">{{ old('description', '') }}</textarea>
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
            $('#title').val('{{ $data["announcement"]["title"] }}');
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
