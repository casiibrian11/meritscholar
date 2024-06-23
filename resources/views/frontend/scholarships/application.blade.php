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

    h2 {
        text-align: center;
        color: white;
    }

    form {
        max-width: 100%;
        margin-top: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    input[type="number"],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    input[type="checkbox"],
    input[type="radio"] {
        margin-right: 5px;
    }

    input[type="file"] {
        margin-top: 5px;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }
    h1 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }
    p {
        color: #666;
        line-height: 1.6;
    }
    a {
        color: #007bff;
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
</style>
<img src="{{ asset('css/logo5.png') }}" style="width:100%;">
<div class="container mt-3 mb-5 main-body">
    @if (empty($data['detail']))
        <div class="row mt-2 justify-content-center">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> You must fill this form first.</div>
            <div class="col-sm-6 pt-0 p-4 border rounded-3" style="background:#006400 !important;color:#FFF !important;">
                <form action="{{ route('application-save') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $data['sy_id'] }}" name="sy_id" readonly>
                    <label>Course</label>
                    <select name="course_id" class="mb-3" id="course_id" required>
                        <option value="" disabled selected>SELECT FROM OPTIONS</option>
                        @foreach ($data['courses'] as $course)
                            <option value="{{ $course['id'] }}">{{ strtoupper($course['course_code']) }} - {{ strtoupper($course['course_name']) }}</option>
                        @endforeach
                    </select>
                    <br />
                    <br />

                    <label>Year Level</label>
                    <select name="year_level" class="mb-3" id="year_level" required>
                        <option value="" disabled selected>SELECT FROM OPTIONS</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                    <br />

                    <label>Section</label>
                    <input type="text" name="section" placeholder="Your section (optional)...">

                    <label>Units Enrolled</label>
                    <input type="number" name="units_enrolled" placeholder="Units enrolled...">

                    <label>GWA</label>
                    <input type="text" name="gwa" placeholder="You general weighted average...">

                    <p class="text-right" style="text-align:right;">
                        <button type="submit" class="btn btn-primary w-50">
                            <b>SUBMIT</b>
                        </button>
                    </p>
                </form>
                </div>
            </div>
    @else
        @if (count($data) > 0)
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <form action="{{ route('application-store') }}" method="POST">
                        @csrf

                        @php
                            $count = 0;
                        @endphp
                        @foreach ($data['offers'] as $offer)
                            @if (now()->gt($offer['date_from']) && now()->lt($offer['date_to']))
                                @php
                                    $count += 1;
                                @endphp
                            @endif
                        @endforeach
                        

                        @if ($count > 0)
                            <div class="col-sm-12 p-5 border rounded-3 shadow">
                                <div class="alert alert-success p-1 px-3" style="text-align:justify;">
                                    <i class="fa fa-info-circle"></i> You can select multiple scholarships to apply.
                                </div>

                                @foreach ($data['offers'] as $offer)
                                    @if (now()->gt($offer['date_from']) && now()->lt($offer['date_to']))
                                    <p class="my-0">
                                        <input type="checkbox" name="offers[]" value="{{ $offer['id'] }}"><b>{{ strtoupper($offer['scholarships']['description']) }}</b>
                                    </p>
                                    @endif
                                @endforeach
                                <br />
                                <br />
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-check"></i> Add to list
                                </button>
                            </div>
                            <br />
                        @endif
                    </form>


                    @if (count($data['applications']) > 0)
                        <div class="col-sm-12 p-2 px-4 border rounded-3 shadow">
                            <h4 class="p-3">
                                <b><i class="fa fa-list"></i> MY APPLICATIONS for the 
                                {{ $data['sy']['semester'] ?? '' }}
                                @if (!empty($data['sy']['semester']) && $data['sy']['semester'] <> 'Summer')
                                    Semester
                                @endif
                                of S.Y.
                                {{ strtoupper($data['sy']['start_year'] ?? '') }}-{{ strtoupper($data['sy']['end_year'] ?? '') }}
                                </b>
                            </h4>

                            @if ($data['applied'] > 0)
                                <div class="alert bg-light">
                            @else
                                <form action="{{ route('application-save') }}" class="alert bg-light" method="POST">
                            @endif
                                <div class="alert alert-danger shadow small p-1 px-3">
                                    <strong>WARNING, READ THIS FIRST!</strong>
                                    <br />Make sure the details below are correct before submitting any application. 
                                    <br />You can no longer
                                    update these information once you have submitted any of your selected applications.
                                </div>
                                @csrf
                                <div class="row mt-1">
                                <input type="hidden" value="{{ $data['detail']['id'] }}" name="id" readonly>
                                <input type="hidden" value="{{ $data['sy_id'] }}" name="sy_id" readonly>
                                <div class="col-sm-12">
                                    <label>Course</label>
                                    <select name="course_id" class="mb-3" id="course_id" required>
                                        <option value="" disabled selected>SELECT FROM OPTIONS</option>
                                        @foreach ($data['courses'] as $course)
                                            <option value="{{ $course['id'] }}">{{ strtoupper($course['course_code']) }} - {{ strtoupper($course['course_name']) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <label>College <small><span class="badge bg-info text-dark">auto-update based from selected course</span></small></label>
                                    <input type="text" class="p-1" value="{{ $data['college_name'] ?? '' }}" readonly>
                                </div>
                                <br />
                                <br />
                                    <div class="col-sm-2">
                                        <label>Year Level</label>
                                        <select name="year_level" class="mb-3" id="year_level" {{ $data['disable'] }} required>
                                            <option value="" disabled selected>SELECT FROM OPTIONS</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Section</label>
                                        <input type="text" name="section" value="{{ $data['detail']['section'] ?? '' }}" {{ $data['disable'] }} placeholder="Your section (optional)...">
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Units Enrolled</label>
                                        <input type="number" name="units_enrolled" value="{{ $data['detail']['units_enrolled'] ?? '' }}" {{ $data['disable'] }} placeholder="Units enrolled...">
                                    </div>
                                    <div class="col-sm-2">
                                        <label>GWA</label>
                                        <input type="text" name="gwa" value="{{ $data['detail']['gwa'] ?? '' }}" {{ $data['disable'] }} placeholder="You general weighted average...">
                                    </div>
                                    

                                    @if ($data['applied'] > 0)
                                        <div class="col-sm-12">
                                        <div class="alert alert-info shadow small">
                                            You can no longer update these information.
                                        </div>
                                        </div>
                                    @else
                                        <div class="col-sm-2">
                                            <br />
                                            <button type="submit" class="btn btn-primary w-100 mt-2">
                                                <b><i class="fa fa-save"></i> UPDATE</b>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @if ($data['applied'] > 0)
                                </div>
                            @else
                                </form>
                            @endif
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> STATUS LEGENDS
                                <ul class="mt-2">
                                    <li><b>This is irreversible!</b> Status will be updated to <span class="badge bg-success">submitted</span> once you click "Complete Application" from the submission of requirements. </li>
                                    <li><span class="badge bg-primary">being reviewed</span> once the administrator viewed your application.</li>
                                    <li><span class="badge bg-success">approved</span> means requirements are valid.</li>
                                    <li><span class="badge bg-danger">denied</span> if there's something wrong with your application.</li>
                                    <li>You will receive an email notification if any status update is made.</li>
                                    <li class="mt-3">
                                        <a href="#" class="btn btn-danger small">
                                            <i class="fa fa-trash"></i> Withdraw Application
                                        </a> will only be accessible if the application can still be withdrawn. This option will not be displayed otherwise. <b>Warning!</b> Trying to forcefully or manually withdrawing your application will automatically disable your account.
                                    </li>
                                </ul>
                            </div>
                            <div class="table-container">
                                <table class="table table-bordered small">
                                    <thead class="bg-success text-white">
                                        <tr>
                                            <th class="w-25">Scholarship</th>
                                            <th>S.Y.</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Date submitted</th>
                                            <th class="text-center">Last updated</th>
                                            <th style="width:180px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['applications'] as $application)
                                        <tr>
                                            <td>
                                                <b>{{ strtoupper($application['scholarship_offers']['scholarships']['description'] ?? '') }}</b>
                                            </td>
                                            <td>
                                                {{ $application['school_years']['semester'] ?? '' }}
                                                @if (!empty($application['school_years']['semester']) && $application['school_years']['semester'] <> 'Summer')
                                                    Semester
                                                @endif
                                                of S.Y.
                                                {{ strtoupper($application['school_years']['start_year'] ?? '') }}-{{ strtoupper($application['school_years']['end_year'] ?? '') }}
                                            </td>
                                            <td class="text-center">
                                                @if (!is_null($application['approved']))
                                                    @if ($application['approved'])
                                                        <span class="badge bg-success">approved</span>
                                                    @else
                                                        <span class="badge bg-danger">denied</span>
                                                    @endif
                                                @endif
                                                
                                                @if (is_null($application['approved']))
                                                    @if ($application['completed'])
                                                        <span class="badge bg-success">submitted</span>
                                                    @else
                                                        <span class="badge bg-danger">incomplete</span>
                                                    @endif
                                                @endif

                                                @if (!is_null($application['under_review']))
                                                    @if ($application['under_review'])
                                                        <span class="badge bg-info">reviewed</span>
                                                    @else
                                                        <span class="badge bg-warning">under review</span>
                                                    @endif
                                                    <br />
                                                @endif
                                                
                                                @if (is_null($application['approved']))
                                                    @if (!is_null($application['request_to_change']))
                                                        @if ($application['request_to_change'])
                                                            <span class="badge bg-info">request to update</span>
                                                        @else
                                                            <span class="badge bg-info">request to change done</span>
                                                            <span class="badge bg-info">updated</span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($application['date_completed']))
                                                    <span class="badge bg-light shadow text-dark border border-dark">
                                                        {{ now()->parse($application['date_completed'])->format('M j, Y h:i a') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($application['date_updated']))
                                                    <span class="badge bg-light shadow text-dark border border-dark">
                                                        {{ now()->parse($application['date_updated'])->format('M j, Y h:i a') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-0">
                                                <a href="/scholarships/{{ $application['sy_id'] }}/application/{{ $application['id'] }}/requirements" 
                                                    class="btn btn-success w-100 small">
                                                    <i class="fa fa-list"></i> Requirements
                                                </a>
                                                @if (is_null($application['approved']) && is_null($application['under_review']))
                                                    <a href="/scholarships/{{ $application['sy_id'] }}/application/{{ $application['id'] }}/withdraw"
                                                        class="btn btn-danger w-100 withdraw small">
                                                        <i class="fa fa-trash"></i> Withdraw Application
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-sm-8 border rounded-4 shadow p-4 alert alert-danger">
                    <center>
                        I'm sorry, there's no scholarship offering as of the moment. <br /> Please wait for further announcements.
                    </center>
                </div>
            </div>
        @endif
    @endif
</div>
<br />
<br />
<br />

<script>
    $(function(){
        $('#course_id').select2({
            width: 'resolve',
        });

        @if (!empty($data['detail']))
            $('#course_id').val("{{ $data['detail']['course_id'] }}").trigger('change');
            $('#year_level').val("{{ $data['detail']['year_level'] }}");
        @endif

        $(document).on('click', '.withdraw', function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: "Withdraw application?",
                text: "Once submitted, this cannot be undone. Your uploaded attachments will also be removed. Proceed anyway?",
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
    });
</script>
@endsection
