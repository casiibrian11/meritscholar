@extends('layouts.admin')

@section('content')
<h3 class="mt-2 p-0"><i class="fa fa-list-alt"></i> Manage Scholarships</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item active"><a href="/manage-scholarships">Manage Scholarships</a></li>
</ol>
<div class="row">
    <div class="col-sm-12 p-0">
        <div class="card p-0 main-body">
            <div class="card-body">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id" id="id" readonly>
                    <div class="form-floating mb-2">
                        <select class="form-control" name="sy_id" id="sy_id" required>
                            <option value="" disabled selected>SELECT SCHOOL YEAR</option>
                            @foreach ($data['school_years'] as $sy)
                                <option value="{{ $sy['id'] }}">{{ $sy['start_year'] }} - {{ $sy['end_year'] }} ( {{ $sy['semester'] }} @if ($sy['semester'] !== 'Summer') Semester @endif)</option>
                            @endforeach
                        </select>
                        <label for="required">S.Y.</label>
                    </div>

                    <div id="scholarships-container"></div>
                </div>
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

        $('#sy_id').on('change', function(){
            var sy_id = $(this).val();

            $.ajax({
                url:"{{ route('load-scholarships') }}",
                method:'POST',
                data:{
                    sy_id:sy_id
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
                        $('#scholarships-container').html(response.html);
                    }
                }
            });
        });

        $(document).on('click','.save-scholarship', function(){
            var sy_id = $(this).data('sy_id');
            var scholarship_id = $(this).data('scholarship_id');
            var id = $('#id_'+scholarship_id).val();
            var date_from = $('#date_from_'+scholarship_id).val();
            var date_to = $('#date_to_'+scholarship_id).val();
            var active = $('#active_'+scholarship_id).is(":checked");

            $.ajax({
                url:"{{ route('manage-scholarships-save') }}",
                method:'POST',
                data:{
                    sy_id:sy_id,
                    id:id,
                    scholarship_id:scholarship_id,
                    date_from:date_from,
                    date_to:date_to,
                    active:active
                },
                dataType:'json',
                success:function(response){
                    console.log(response);
                    loaderx();
                    $('#modal').removeClass('d-none');
                    $('.modal-backdrop').removeClass('d-none'); 
                    if(response.success) {
                        $('#save_scholarship_'+scholarship_id).removeClass('btn-success').addClass('btn-warning');
                        $('#save_scholarship_'+scholarship_id).html('<i class="fa fa-save"></i>');
                        $('#id_'+scholarship_id).val(response.id);

                        $('#status_'+scholarship_id+' .badge').removeClass('d-none');
                        $('#delete_btn_'+scholarship_id).removeClass('d-none');
                        customAlert('success', response.success);
                    } else {
                        console.log(response.error);
                        customAlert('error', response.error);
                    }
                },
                error:function(data){
                    loaderx();
                    $('#modal').removeClass('d-none');
                    $('.modal-backdrop').removeClass('d-none');
                    console.log(data);
                    var message = "";
                    var errors = data.responseJSON;
                    $.each( errors.errors, function(key, value) {
                        message += '<li>'+ value +'</li>';
                    });
                    customAlert('error', message);
                } 
            });
        });

        $(document).on('click','.delete-item', function(){
            var scholarship_id = $(this).data('scholarship_id');
            var id = $('#id_'+scholarship_id).val();
            Swal.fire({
                title: 'Are you sure?',
                text: "Once submitted, you will not be able to undo this.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed.'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:"{{ route('manage-scholarships-delete') }}",
                        method:'POST',
                        data:{
                            id:id,
                        },
                        dataType:'json',
                        success:function(response){
                            console.log(response);
                            loaderx();
                            $('#modal').removeClass('d-none');
                            $('.modal-backdrop').removeClass('d-none'); 

                            if(response.success) {
                                $('#save_scholarship_'+scholarship_id).addClass('btn-success').removeClass('btn-warning');
                                $('#save_scholarship_'+scholarship_id).html('<i class="fa fa-plus"></i>');
                                $('#id_'+scholarship_id).val("");
                                $('#status_'+scholarship_id+' .badge').addClass('d-none');

                                $('#active_'+scholarship_id).prop("checked", false);

                                $('#delete_btn_'+scholarship_id).addClass('d-none');
                                
                                customAlert('success', response.success);
                            } else {
                                console.log(response.error);
                                customAlert('error', response.error);
                            }
                        },
                        error:function(data){
                            loaderx();
                            $('#modal').removeClass('d-none');
                            $('.modal-backdrop').removeClass('d-none');
                            console.log(data);
                            var message = "";
                            var errors = data.responseJSON;
                            $.each( errors.errors, function(key, value) {
                                message += '<li>'+ value +'</li>';
                            });
                            customAlert('error', message);
                        } 
                    });
                }
            });
        });
    })
</script>
@endsection
