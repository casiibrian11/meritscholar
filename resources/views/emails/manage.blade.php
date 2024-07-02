@extends('layouts.admin')

@section('content')
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
            <form action="{{ route('email-template-save') }}" method="POST" id="email-template">
                <div class="modal-body">
                    <p class="alert alert-primary h5">
                        <i class="fa fa-info-circle"></i> This email notification template will be sent to the user when their application <b>{{ $data['statuses'][$data['template']] }}</b>
                    </p>
                    <div class="alert alert-info small">
                        <strong><i class="fa fa-info-circle"></i> Use the following wildcard names for dynamic email contents;</strong>
                        <ul class="m-0 my-2">
                            <li><span class="badge bg-light text-dark">:applicant_name</span> - will display the applicant's first name and last name</li>
                            <li><span class="badge bg-light text-dark">:scholarship</span> - scholarship that the applicant applied for</li>
                            <li><span class="badge bg-light text-dark">:semester</span></li>
                            <li><span class="badge bg-light text-dark">:school_year</span> - school year of the scholarship applied for</li>
                            <li><span class="badge bg-light text-dark">:status</span> - status of the application eg. <b>submitted</b>, <b>under review</b>, <b>approved</b>, <b>denied</b> </li>
                        </ul>
                        Example:
                        <div class="alert alert-light">
                            Hi :applicant_name,<br /><br />
                            Your application for the :scholarship for the :semester of the :school_year is|has been :status.
                        </div>
                        Output:
                        <div class="alert alert-light">
                            Hi Firstname Lastname,<br /><br />
                            Your application for the ACADEMIC SCHOLARSHIP for the 1st semester of the S.Y. 2024-2025 is|has been 
                            @if ($data['template'] == 'completed')
                                submitted.
                            @else
                            {{ $data['template'] }}.
                            @endif
                        </div>
                    </div>
                    @csrf
                    <input type="hidden" name="email_content" id="email_content" value="{{ $data['email']['email_content'] ?? '' }}" readonly>
                    <input type="hidden" name="status" id="status" value="{{ $data['template'] }}" readonly>
                    <div class="form-group mb-2">
                        <label for="content">Email Subject <span class="required">*</span></label>
                        <input type="text" name="subject" id="subject" class="form-control"
                            placeholder="Email subject..." value="{{ $data['email']['subject'] ?? '' }}" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="content">Content <span class="required">*</span></label>
                        <textarea class="form-control" placeholder="Content to post as announcement..." 
                            style="height:100px;" id="editor">{{ old('email_content', '') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="submit" class="btn btn-primary save-template">Save</button>
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

        @if (!empty($data['email']))
            var content = $('#email_content').val();
            CKEDITOR.instances['editor'].setData(content);
        @endif

        $('.save-template').on('click', function(){
            var editorText = CKEDITOR.instances.editor.getData();

            $('#email_content').val(editorText);
            $('#email-template').trigger('submit');
        });
    });
</script>
@endsection
