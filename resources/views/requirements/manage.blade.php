@extends('layouts.admin')

@section('content')
<h3 class="mt-2 p-0"><i class="fa fa-list-alt"></i> Manage Requirement</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/requirements">Requirements</a></li>
    <li class="breadcrumb-item active">Manage Requirement</li>
</ol>
<div class="row">
    <div class="col-sm-12 p-0">
        <div class="card p-0 main-body">
            <div class="card-body px-5">
            <form action="{{ route('requirements-save') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p class="alert alert-info small">
                        <strong><i class="fa fa-info-circle"></i> NOTE:</strong> Fields marked with <span class="required">*</span> are required.
                    </p>
                    @csrf
                    <input type="hidden" name="id" id="id" readonly>
                    <div class="form-floating mb-2">
                        <input type="text" id="label" name="label" class="form-control" 
                            placeholder="Label" autocomplete="off" value="{{ old('label', '')}}" required>
                            <label for="label">Label <span class="required">*</span></label>
                    </div>

                    <div class="form-floating mb-2">
                        <textarea class="form-control" placeholder="Detailed description of the requirement..." style="height:100px;" name="description" id="description">{{ old('description', '') }}</textarea>
                        <label for="description">Description</label>
                    </div>

                    <div class="form-floating mb-2">
                        <select class="form-control" name="required" id="required" required>
                            <option value="yes" selected>Yes</option>
                            <option value="no">No</option>
                        </select>
                        <label for="required">Required? <span class="required">*</span></label>
                    </div>

                    <div class="form-floating mb-2">
                        <select class="form-control type-select" name="type" id="type" required>
                            <option value="" disabled selected>SELECT</option>
                            <option value="text">text</option>
                            <option value="number">number</option>
                            <option value="image">image</option>
                            <option value="document">document</option>
                        </select>
                        <label for="type">Type <span class="required">*</span></label>
                    </div>

                    <div id="file-type-container"></div>

                    @if (!empty($requirement['sample']) && \Illuminate\Support\Facades\Storage::exists('/public/requirements/'.$requirement["sample"]))
                        <div class="row mt-2">
                            <div class="col-sm-4">
                                <a href="/storage/requirements/{{ $requirement['sample'] }}" target="_blank">
                                    <img src="/storage/requirements/{{ $requirement['sample'] }}" style="width:100%;max-width:100%;">
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer mt-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(function(){

        @if (!empty($requirement))
            var id = "{{ $requirement['id'] }}";
            var label = "{{ $requirement['label'] }}";
            var description = "{{ $requirement['description'] }}";
            var oldType = "{{ $requirement['type'] }}";
            var required = "{{ $requirement['required'] ? 'yes' : 'no' }}";
            var file_type = "{{ implode(',', $requirement['file_type']) }}";
            file_type = file_type.split(',');
            
            $('#id').val(id);
            $('#label').val(label);
            $('#description').val(description);
            $('#required').val(required);
            $('.type-select').val(oldType);
            setTimeout(function(){
                $('.type-select').trigger('change');
            },1000);

            setTimeout(function(){
                $('.file_type').val(file_type).trigger("change");
            },2000);

        @else
            var oldType = "{{ old('type', '') }}";
            var required = "{{ old('required', '') }}";
            if (required !== "") {
                $('#required').val(required);
            }

            if (oldType !== "") {
                $('.type-select').val(oldType);
                setTimeout(function(){
                    $('.type-select').trigger('change');
                },1000);
            }
        @endif

        $('.type-select').on('change', function(){
            var type = $(this).val();
  
            if (type == 'text' || type == 'number') {
                $('#file-type-container').html("");
                return;
            }

            $.ajax({
                url:"{{ route('file-types') }}",
                method:'POST',
                data:{
                    type:type
                },
                dataType:'json',
                beforeSend:function(){
                    loader();
                    $('#modal').addClass('d-none'); 
                    $('.modal-backdrop').addClass('d-none');
                },
                success:function(response){
                    loaderx();
                    $('#modal').removeClass('d-none');
                    $('.modal-backdrop').removeClass('d-none'); 

                    if(response.error) {
                        customAlert('error', response.error);
                    } else {
                        $('#file-type-container').html(response.html);
                    }
                }
            });
        });
    })
</script>
@endsection
