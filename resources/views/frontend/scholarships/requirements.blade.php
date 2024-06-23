@extends('layouts.app')

@section('content')
<style>
    .shadow{
        box-shadow:0 0 10px 5px #aaa;
    }
    a{
        text-decoration:none !important;
    }
    a:hover{
        text-decoration-color:transparent !important;
    }
    .alert-block{
        position:fixed !important;
        z-index:100 !important;
    }
    .swal2-title{
        font-size:20px !important;
    }
</style>
<img src="{{ asset('css/logo5.png') }}" style="width:100%;">
<div class="container main-body mt-3">
    <a href="/scholarships/{{ $data['sy_id'] }}/application" class="btn btn-success mt-2 px-5">
        <i class="fa fa-list"></i> My applications
    </a>
    <br />
    <br />
    <div class="row justify-content-center">
        <div class="col-sm-12 p-2 border rounded-3 shadow mb-5">
            @if (!empty($data['application']['scholarship_offers']['scholarships']['requirements']))
                @php
                    $requirements = explode(',', $data['application']['scholarship_offers']['scholarships']['requirements']);
                @endphp
                
                @if (!empty($requirements))
                    <h4 class="p-3">
                        
                            <i class="fa fa-list"></i> 
                            Requirements for 
                            <b>
                                {{ strtoupper($data['application']['scholarship_offers']['scholarships']['description']) }}
                            </b>
                            for the
                            <b>
                                {{ $data['sy']['semester'] ?? '' }}
                                @if (!empty($data['sy']['semester']) && $data['sy']['semester'] <> 'Summer')
                                    Semester
                                @endif
                                of S.Y.
                                {{ strtoupper($data['sy']['start_year'] ?? '') }}-{{ strtoupper($data['sy']['end_year'] ?? '') }}
                            </b>
                    </h4>

                    @if (is_null($data['application']['approved']))
                        @if ($data['application']['completed'])
                            <div class="alert alert-success">
                                <center>
                                    Your application for this scholarship has been submitted. Please wait for notifications or further annoucements.
                                </center>
                            </div>
                        @endif
                    @else
                        @if ($data['application']['approved'])
                            <div class="alert bg-success text-white">
                                <center>
                                    Congratulations! Your application has been approved.
                                </center>
                            </div>
                        @else
                            <div class="alert bg-danger text-white p-1">
                                <center>
                                    I'm sorry, Your application has been denied.
                                </center>
                            </div>
                        @endif
                    @endif
                    <div class="table-container">
                    <table class="table table-bordered small">
                        <thead>
                            <tr>
                                <th class="w-25">Requirement</th>
                                <th class="w-25">Description</th>
                                <th class="text-center" style="width:50px;">Type</th>
                                <th class="text-center" style="width:50px !important;">Formats</th>
                                <th style="width:100px;" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requirements as $key => $value)
                                @if (!empty($data['requirements'][$value]))
                                    @php
                                        $requirement = $data['requirements'][$value];
                                        $fileTypes = explode(',', $requirement['file_type']);
                                    @endphp
                                <tr>
                                    <td>
                                        @if ($requirement['required'])
                                            <span class="badge bg-danger">required</span>
                                        @else
                                            <span class="badge bg-info">optional</span>
                                        @endif
                                        <br />
                                        <b>{{ $requirement['label'] }}</b>
                                    </td>
                                    <td style="text-align:justify">{{ $requirement['description'] }}</td>
                                    <td class="text-center">
                                        {{ $requirement['type'] }}
                                    </td>
                                    <td class="text-center">
                                        @if (count($fileTypes) > 0)
                                            @foreach ($fileTypes as $type)
                                                <span class="badge bg-info">{{ $type }}</span>
                                            @endforeach
                                        @endif

                                        @if (!in_array($requirement['type'], ['image', 'document']))
                                            <span class="badge bg-info">{{ $requirement['type'] }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($data['submitted'][$requirement['id']]))
                                            @if (!is_null($data['submitted'][$requirement['id']]['request_to_change']))
                                                @if ($data['submitted'][$requirement['id']]['request_to_change'])
                                                    <span class="badge bg-danger">change this attachment</span>
                                                    <span class="badge bg-warning text-dark">for compliance</span>
                                                @else
                                                    <span class="badge bg-info">request to change done</span>
                                                    <span class="badge bg-info">updated</span>
                                                @endif
                                                <br />
                                            @endif

                                            @if (in_array($requirement['type'], ['image', 'document']))
                                            <a href="/storage/submitted-requirements/{{ $data['submitted'][$requirement['id']]['attachment'] }}" target="_blank">
                                                <span class="badge bg-success"><i class="fa fa-paperclip"></i> view attachment</span>
                                            </a>
                                            @else
                                                <span class="badge bg-success">filled up</span>
                                            @endif

                                            @if (!empty($data['submitted'][$requirement['id']]['note']))
                                                <a href="#" class="view-note" data-note="{{ $data['submitted'][$requirement['id']]['note'] }}">
                                                    <span class="badge bg-secondary"><i class="fa fa-pen"></i> view note from admin</span>
                                                </a>
                                            @endif

                                            @if (is_null($data['submitted'][$requirement['id']]['request_to_change']))
                                                @if (!$data['application']['completed'])
                                                    <a href="/attachment/{{ $data['submitted'][$requirement['id']]['id'] }}/remove" class="delete">
                                                        <span class="badge bg-danger"><i class="fa fa-times"></i> remove</span>
                                                    </a>
                                                @endif
                                            @endif
                                        @else
                                            @if (in_array($requirement['type'], ['image', 'document']))
                                                <div class="badge bg-danger m-auto">no attachment</div>
                                            @else
                                                <span class="badge bg-danger">unfulfilled</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                @else
                @endif
            @endif
            
            @if (!$data['application']['completed'])
                @if ($data['ok'])
                    @if ($data['officeHoursOnly'])
                        @if ($data['pastOfficeHours'])
                            <div class="alert alert-danger">
                                <center>
                                    <strong>NOTE:</strong> You are only allowed to complete applications during office hours
                                    @if (!empty($data['settings']['office_hours_start']) 
                                        && !empty($data['settings']['office_hours_end'])
                                        && !empty($data['settings']['office_hours_start']) !== false
                                        && !empty($data['settings']['office_hours_end']) !== false)
                                        from <b>{{ now()->parse($data['settings']['office_hours_start'])->format('h:i a') }}</b>
                                        to <b>{{ now()->parse($data['settings']['office_hours_end'])->format('h:i a') }}</b>
                                    @endif
                                    @if (!$data['weekendsAllowed'])
                                        during weekdays only.
                                    @else
                                        including weekends.
                                    @endif
                                </center>
                            </div>
                        @else
                            @if (now()->isWeekend() && !$data['weekendsAllowed'])
                                <div class="alert alert-danger">
                                    <center>
                                        <strong>NOTE:</strong> You are only allowed to complete applications during weekdays.
                                    </center>
                                </div>
                            @else
                                <form action="{{ route('complete-application') }}" method="POST" id="submit-form">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $data['application']['id'] }}" readonly required>
                                    <input type="hidden" name="sy_id" value="{{ $data['sy_id'] }}" readonly required>
                                    <p style="text-align:right;">
                                        <button type="button" class="btn btn-success px-5 p-3" id="submit-btn">
                                            <i class="fa fa-check"></i> Complete Application
                                        </button>
                                    </p>
                                </form>
                            @endif
                        @endif
                    @endif
                @else
                    <div class="alert alert-info">
                        <center>
                            <strong>NOTE:</strong> You are only allowed to complete your application once all required attachments are added.
                        </center>
                    </div>
                @endif
            @endif

            @if (count($data['notes']) > 0)
                <div class="alert alert-info">
                    Hey, there's some notes sent to you related to this application,
                    <br />
                    <br />
                    @foreach ($data['notes'] as $note)
                        <div class="alert bg-light p-0 mx-3 mb-2 shadow-sm">
                            <div class="row p-0">
                                <div class="col-sm-1 text-center" style="margin:auto; !important">
                                    <div class="border w3-circle bg-success text-white p-0 py-3 shadow-sm" style="margin-left:10px !important;">
                                        <i class="fa fa-user m-0 h5"></i>
                                    </div>
                                </div>
                                <div class="col-sm-11 p-0">
                                    <p class="bg-white w-100 text-dark shadow-sm p-3 m-0 rounded-2" style="font-weight:normal;text-align:left;font-size:15px;">
                                        {{ $note['note'] }}
                                        <br />
                                        <br />
                                        <small>
                                            <span class="badge text-dark" style="font-weight:normal;text-align:left;">
                                                {{ now()->parse($note['created_at'])->format('F j, Y h:i a') }}
                                                ({{ now()->parse($note['created_at'])->diffForHumans() }})
                                            </span>
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>


        @if (!$data['application']['completed'] && is_null($data['application']['under_review']) && is_null($data['application']['approved  ']))
        <div class="col-sm-12 p-4 border rounded-3" style="background:#006400 !important;color:#FFF !important;">
            @if (!empty($data['application']['scholarship_offers']['scholarships']['requirements']))
                @php
                    $requirements = explode(',', $data['application']['scholarship_offers']['scholarships']['requirements']);
                @endphp
                <form action="{{ route('requirements-submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="application_id" value="{{$data['application']['id']}}" readonly>
                    @if (!empty($requirements))
                        @foreach ($requirements as $key => $value)
                            @if (!empty($data['requirements'][$value]))
                                @php
                                    $requirement = $data['requirements'][$value];
                                    $fileTypes = explode(',', $requirement['file_type']);
                                @endphp
                                <div class="form-group mb-5">
                                    <label><li>{{ $requirement['label'] }}</li></label>
                                    @if (in_array($requirement['type'], ['image', 'document']))
                                        <a href="/storage/requirements/{{ $requirement['sample'] }}" class="small badge bg-secondary" target="_blank">
                                            <i class="fa fa-folder-open"></i> View Sample
                                        </a>
                                    @endif
                                    @if (!empty($data['submitted'][$requirement['id']]))
                                        @if (!is_null($data['submitted'][$requirement['id']]['request_to_change']))
                                            @if ($data['submitted'][$requirement['id']]['request_to_change'])
                                                <span class="badge bg-danger">change this attachment</span>
                                                <span class="badge bg-warning text-dark">for compliance</span>
                                            @else
                                                <span class="badge bg-info">request to change done</span>
                                                <span class="badge bg-info">updated</span>
                                            @endif
                                            <br />
                                        @endif
                                    @endif
                                    <p style="text-align:justify" class="alert alert-default small p-1 px-2">{{ $requirement['description'] }}</p>

                                    @if (in_array($requirement['type'], ['image', 'document']))
                                        <input type="file" allowed="{{ $requirement['file_type'] }}"
                                            name="attachment[{{$requirement['id']}}]" class="form-control"
                                            @if ($requirement['required'] && empty($data['submitted'][$requirement['id']])) required @endif>
                                        @if (!empty($data['submitted'][$requirement['id']]))
                                            @if ($requirement['type'] == 'image')
                                                <br />
                                                <img data-fancybox="gallery" data-caption="{{ $data['submitted'][$requirement['id']]['attachment'] }}" src="/storage/submitted-requirements/{{ $data['submitted'][$requirement['id']]['attachment'] }}" alt="Sample" style="width:30%; max-width:100%;">
                                            @else
                                                <span class="badge bg-warning text-dark"><i class="fa fa-paperclip"></i> attachment has been added</span>
                                            @endif
                                        @endif
                                    @else
                                        <input type="{{ $requirement['type'] }}" name="attachment[{{$requirement['id']}}]" id="attachment" class="form-control"
                                            value="{{ $data['submitted'][$requirement['id']]['attachment'] ?? '' }}"
                                            @if ($requirement['required'] && empty($data['submitted'][$requirement['id']])) required @endif>
                                    @endif
                                    {{--
                                    @if (in_array($requirement['type'], ['image', 'document']))
                                        @if ($requirement['type'] == 'image')
                                            <p class="text-center mt-2">
                                                <a href="/storage/requirements/{{ $requirement['sample'] }}" target="_blank">
                                                    <img src="/storage/requirements/{{ $requirement['sample'] }}" alt="Sample"
                                                        style="width:30%; max-width:100%;">
                                                </a>
                                            </p>
                                        @endif
                                    @endif
                                    --}}
                                </div>
                            @endif
                        @endforeach
                    @else
                    @endif
                    <button type="submit" class="btn btn-outline-secondary bg-success text-white w-100 p-3">
                        <i class="fa fa-paper-plane"></i> Submit Requirements
                    </button>
                </form>
            @endif
        </div>
        @endif
    </div>
</div>
<br />
<br />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
    $(function(){
        Fancybox.bind("[data-fancybox]");

        $(document).on('click', '.delete', function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: "Are you sure you want to remove this attachment?",
                text: "Once submitted, this cannot be undone. Proceed anyway?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        $(document).on('click', '#submit-btn', function(e){
            Swal.fire({
                title: "Are you sure?",
                text: "Once verified, this cannot be undone. If you are confident with your attachments, you can go ahead and proceed.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#submit-form').trigger('submit');
                }
            });
        });

        $(document).on('click', '.view-note', function(e){
            var note = $(this).data('note');
            Swal.fire(note);
        });
    });
</script>
@endsection
