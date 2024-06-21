@extends('layouts.admin')

@section('content')
<h3 class="mt-2 p-0"><i class="fa fa-list"></i> Application Requirements</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/scholarship/applications">List of applications</a></li>
    <li class="breadcrumb-item">Check requirements</li>
</ol>
<div class="row">
    <div class="col-sm-12 p-0">
        @if (count($data['requirements']) > 0)
        <div class="card p-0 main-body">
            <div class="card-body">
                <div class="row px-3">
                @php
                    $user = $data['application']['users'];
                    $sy = $data['application']['school_years'];
                    $offer = $data['application']['scholarship_offers'];
                    $detail = $data['details'];
                @endphp
                <div class="badge bg-light text-dark p-3">
                    <table class="w3-table text-dark" style="font-weight:normal;">
                        <tr>
                            <td>
                                Scholarship:<b> {{ strtoupper($offer['scholarships']['description']) }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                S.Y:<b> {{ strtoupper($sy['start_year']) }} - {{ strtoupper($sy['end_year']) }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Semester:
                                <b> {{ $sy['semester'] }}
                                    @if ($sy['semester'] <> 'Summer')
                                        Semester
                                    @endif
                                </b>
                            </td>
                        </tr>
                        <tr>
                            <td><br /><br /></td>
                        </tr>
                        <tr>
                            <td class="w-25">Name: <b>{{ strtoupper($user['last_name']) }}, {{ strtoupper($user['first_name']) }} {{ strtoupper($user['middle_name']) }} {{ strtoupper($user['name_extension']) }}</b></td>
                        </tr>
                        <tr>
                            <td>Course: <b>({{ strtoupper($detail['courses']['course_code'] ?? '') }}) {{ strtoupper($detail['courses']['course_name'] ?? '') }}</b></td>
                        </tr>
                        <tr>
                            <td class="w-25">Year &amp; Section: <b>{{ strtoupper($detail['year_level'] ?? '') }} - {{ strtoupper($detail['section'] ?? '') }}</b></td>
                        </tr>
                        <tr>
                            <td>College: <b>({{ strtoupper($detail['courses']['colleges']['college_code'] ?? '') }}) {{ strtoupper($detail['courses']['colleges']['college_name'] ?? '') }}</b></td>
                        </tr>

                        <tr>
                            <td class="w-25">GWA: <b>{{ strtoupper($detail['gwa'] ?? '') }}</b></td>
                        </tr>
                        <tr>
                            <td class="w-25">Units Enrolled: <b>{{ strtoupper($detail['units_enrolled'] ?? '') }}</b></td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-12 my-4 alert alert-primary">
                    <p>
                        Status:
                        @if ($data['application']['request_to_change'])
                            <span class="badge bg-info">requested some changes</span>
                        @endif

                        @if (!is_null($data['application']['request_to_change']) && !$data['application']['request_to_change'])
                            <span class="badge bg-info">request to change acknowledged</span>
                            <span class="badge bg-info">updated</span>
                        @endif
                        
                        @if ($data['application']['request_to_change'] && !$data['application']['completed'])
                            <span class="badge bg-info">for compliance</span>
                        @endif
                        @if ($data['application']['completed'])
                            <span class="badge bg-primary">completed</span>
                        @endif

                        @if (!is_null($data['application']['under_review']))
                            @if ($data['application']['under_review'])
                                <span class="badge bg-info">reviewed</span>
                            @else
                                <span class="badge bg-info">under review</span>
                            @endif
                        @endif

                        <b id="application-status">
                            @if (is_null($data['application']['approved']))
                                <span class="badge bg-warning">for approval</span>
                            @else
                                @if ($data['application']['approved'])
                                    <span class="badge bg-success">approved</span>
                                @else
                                    <span class="badge bg-danger">denied</span>
                                @endif
                            @endif
                        </b>
                    </p>
                    @if (is_null($data['application']['approved']))
                        @if (!$data['application']['request_to_change'])
                            <a href="/scholarship/applications/{{ $data['application']['id'] }}/update-status?approved=true"
                                class="btn btn-sm btn-success p-0 px-3 approval"
                                data-status="approved">
                                <i class="fa fa-check"></i> Approve
                            </a>
                        @endif
                        <a href="/scholarship/applications/{{ $data['application']['id'] }}/update-status?approved=false" 
                            class="btn btn-sm btn-danger p-0 px-3 approval"
                            data-status="denied">
                            <i class="fa fa-times"></i> Deny
                        </a>
                    @endif

                    {{--
                    <button type="button" class="btn btn-sm btn-info text-white p-0 px-3 update-status undo-update @if(is_null($data['application']['approved'])) d-none @endif" 
                        data-approved="false"
                        data-undo="true"
                        data-id="{{ $data['application']['id'] }}"
                        data-action="update-application"
                        data-status="">
                        <i class="fa fa-undo"></i> Undo update
                    </button>
                    --}}

                    <button type="button" class="btn btn-sm btn-secondary p-0 px-3" data-toggle="modal" data-target="#modal"
                        data-backdrop="static" data-keyboard="false">
                        <i class="fa fa-plus"></i> Add note
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary p-0 px-3" data-toggle="modal" data-target="#notes"
                        data-backdrop="static" data-keyboard="false">
                        <i class="fa fa-folder-open"></i> View notes
                    </button>
                </div>
                @foreach ($data['requirements'] as $requirement)
                    @php
                        $requirements = $requirement['requirements'];
                        $user = $requirement['users'];
                    @endphp
                    <li class="w-100">
                        <b>
                            {{ $requirements['label'] }}
                        </b>
                    </li>
                    <div class="col-sm-12 px-4">
                        <div class="alert alert-info p-1 px-2">
                            <small>
                                {{ $requirements['description'] }}
                            </small>
                        </div>
                    </div>
                    <div class="row">
                    @if (in_array($requirements['type'], ['image', 'document']))
                        <div class="col-sm-4">
                            <center>
                                <img data-fancybox="attachments" class="mb-4" src="/storage/submitted-requirements/{{ $requirement['attachment'] }}" alt="{{ $requirement['attachment'] }}" style="width:100%;max-width:100%;">
                            </center>
                        </div>
                        <div class="col">
                            @if (in_array($requirements['type'], ['image', 'document']))
                                <a href="/scholarship/applications/{{ $requirement['id'] }}/requirements/download?{{ time() }}" 
                                    target="_blank" class="btn btn-sm btn-light p-0 px-3 shadow border border-dark">
                                    <i class="fa fa-download"></i> Download Attachment
                                </a>
                                <br />
                            @endif

                            @if (!is_null($requirement['request_to_change']))
                                @if ($requirement['request_to_change'])
                                    <span class="badge bg-info">request to change submitted</span>
                                    <br />
                                @else
                                    <span class="badge bg-success">requested to change acknowledged by the applicant</span>
                                    <span class="badge bg-success">updated</span>
                                    {{--
                                    <br />
                                    <br />
                                    <a href="/scholarship/applications/{{ $requirement['id'] }}/request-to-change/{{ $data['application']['id'] }}" 
                                        class="btn btn-sm btn-secondary mb-2 request-to-change">
                                        <i class="fa fa-edit"></i> Request to change again
                                    </a>
                                    --}}
                                @endif
                            @else
                                @if (is_null($data['application']['approved']))
                                    <a href="/scholarship/applications/{{ $requirement['id'] }}/request-to-change/{{ $data['application']['id'] }}" class="btn btn-sm btn-secondary mb-2 request-to-change">
                                        <i class="fa fa-edit"></i> Request to change
                                    </a>
                                @endif
                            @endif
                            <br />
                            {{--
                            <p>
                                Status: 
                                <b id="status_{{ $requirement['id'] }}">
                                    @if (is_null($requirement['approved']))
                                        <span class="badge bg-warning">for approval</span>
                                    @else
                                        @if ($requirement['approved'])
                                            <span class="badge bg-success">approved</span>
                                        @else
                                            <span class="badge bg-danger">denied</span>
                                        @endif
                                    @endif
                                </b>
                            </p>
                            <button type="button" class="btn btn-success p-0 px-3 update-status" data-approved="true"
                                data-id="{{ $requirement['id'] }}"
                                data-status="">
                                <i class="fa fa-check"></i> Approve
                            </button>
                            <button type="button" class="btn btn-danger p-0 px-3 update-status" data-approved="false"
                                data-id="{{ $requirement['id'] }}"
                                data-status="">
                                <i class="fa fa-times"></i> Deny
                            </button>
                            <br />
                            <br />
                            --}}
                            <br />
                            <label>ADD NOTE TO THIS ATTACHMENT</label> <small><i>(if any...)</i></small>
                            <textarea name="note" id="note_{{ $requirement['id'] }}" class="form-control shadow"
                                style="resize:none;" placeholder="Tell something about this attachment, if any..."
                                rows="6">{{ $requirement['note'] }}</textarea>
                            @if (is_null($data['application']['approved']))
                            <button type="button" class="btn btn-success p-0 px-3 mt-2 update-status"
                                data-id="{{ $requirement['id'] }}"
                                data-approved=""
                                data-status="yes">
                                <i class="fa fa-save"></i> SAVE NOTE
                            </button>
                            @endif
                        </div>
                    @else
                        <div class="col-sm-12 px-3">
                            <input type="text" class="form-control shadow-sm" value="{{ $requirement['attachment'] }}" readonly>
                        </div>
                    @endif
                    </div>
                @endforeach
                </div>
            </div>
        </div>
        @else
            <br />
            @include('layouts.partials._no-record')
        @endif
    </div>
</div>

<!--- MODAL -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="z-index:1 !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
            NOTE
        </h5>
        <button type="button" class="btn" data-dismiss="modal" aria-label="Close">
          <span class="h4" aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('save-note') }}" method="POST">
        <div class="modal-body">
            @csrf
            <input type="hidden" name="id" id="id" value="{{ $data['application']['id'] }}" readonly>
            <div class="form-floating mb-2">
                <textarea type="text" id="note" name="note" class="form-control" 
                    placeholder="Course Name" autocomplete="off" style="height:200px;resize:none;" required></textarea>
                    <label for="name">Add your note here...</label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-save">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade modal-lg" id="notes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog" role="document" style="z-index:1 !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel2">
            ADDED NOTES
        </h5>
        <button type="button" class="btn" data-dismiss="modal" aria-label="Close">
          <span class="h4" aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
            @if (count($data['notes']) > 0)
                @foreach ($data['notes'] as $note)
                  <div class="border border-1 rounded-2 p-2 px-3 shadow-sm alert alert-secondary mb-3">
                    <p class="m-0" style="text-align:right;">
                     <a href="/scholarship/applications/{{ $note['id'] }}/delete-note">
                        <i class="fa fa-times"></i>
                     </a>
                     </p>
                     <p class="mb-0">
                        <span class="badge bg-secondary shadow p-1 tiny px-3" style="font-weight:normal;">
                            <small>
                                {{ now()->parse($note['created_at'])->format('F j, Y h:i a') }}
                            </small>
                        </span> <br /> <br />
                        {{ $note['note'] }}
                    </p>
                  </div>
                @endforeach
            @else
                @include('layouts.partials._no-record')
            @endif
        </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
    $(function(){
        Fancybox.bind("[data-fancybox]");

        $('.update-status').on('click', function(){
            var id = $(this).data('id');
            var approved = $(this).data('approved');
            var note = $('#note_'+id).val();
            var status = $(this).data('status');
            var action = $(this).data('action');
            var undo = $(this).data('undo');

            $.ajax({
                url:"{{ route('update-status') }}",
                method:'POST',
                data:{
                    id:id,
                    approved:approved,
                    note:note,
                    status:status,
                    action:action,
                    undo:undo
                },
                dataType:'json',
                beforeSend:function(){
                    loader();
                    $('#modal').addClass('d-none'); 
                    $('.modal-backdrop').addClass('d-none');
                },
                success:function(response){
                    console.log(response);
                    loaderx();
                    $('#modal').removeClass('d-none');
                    $('.modal-backdrop').removeClass('d-none');

                    if (action == 'update-application') {
                        var status = $('#application-status');
                        if (undo) {
                            status.html('<span class="badge bg-warning">for approval</span>');
                            customAlert('success', 'Application status has been updated.');
                            $('.undo-update').addClass('d-none');
                            return;
                        }

                        if (approved) {
                            status.html('<span class="badge bg-success">approved</span>');
                        } else {
                            status.html('<span class="badge bg-danger">denied</span>');
                        }
                        $('.undo-update').removeClass('d-none');
                        customAlert('success', 'Application status has been updated.');
                        return;
                    }

                    var status = $('#status_'+id);
                    if (approved !== 'none') {
                        if (approved) {
                            status.html('<span class="badge bg-success">approved</span>');
                        } else {
                            status.html('<span class="badge bg-danger">denied</span>');
                        }
                    }
                    
                }
            });
        });

        $('.request-to-change').on('click', function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: 'Notify applicant to change this attachment?',
                text: "Once submitted, this will be removed from the list of submitted applications until the applicant re-updates this application.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed.'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        $('.approval').on('click', function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            var status = $(this).data('status');
            
            if (status == 'approved') {
                status = 'Approve application?';
            } else {
                status = 'Deny application?';
            }

            Swal.fire({
                title: status,
                text: "Once processed, updated status will be irreversible. Proceed?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed.'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    })
</script>
@endsection
