@extends('layouts.plain')

@section('content')
<style>
    .selected {
        border-bottom: 3px solid #555 !important;
    }
    .badge{
        color:#000 !important;
    }
    input, select{
        border:1px solid #000 !important;
    }
</style>
<h3 class="mt-2 p-0"><i class="fa fa-list"></i> Masterlist of applications</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item active">Masterlist of applications</li>
</ol>
<div class="row">
    <div class="col-sm-12 p-0">
        <div class="card p-0 main-body bg-light">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-12">
                        <a href="/scholarship/applications/list" 
                            type="button" 
                            class="btn btn-sm btn-outline-secondary @if (empty($data['sy_id'])) bg-secondary text-white @endif">
                            CLEAR SEARCH FILTERS
                        </a>
                        <br />
                        <br />
                        <form action="" method="GET" class="mb-3 alert alert-success shadow">
                        <div class="row px-3">
                            @if (count($data['school_years']) > 0)
                                <div class="col-sm-4 px-0">
                                    <label for="sy_id">SCHOOL YEAR</label>
                                    <select name="sy_id" id="sy_id" class="w-100">
                                        <option value="" selected>SELECT SCHOOL YEAR</option>
                                        @foreach ($data['school_years'] as $sy)
                                            <option value="{{ $sy['id'] }}">
                                                {{ $sy['semester'] }}
                                                @if ($sy['semester'] <> 'Summer')
                                                    Semester
                                                @endif
                                                of S.Y.
                                                {{ $sy['start_year'] }} - {{ $sy['end_year'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if (count($data['scholarships']) > 0)
                                <div class="col-sm-6">
                                    <label for="scholarship_id">SCHOLARSHIP</label>
                                    <select name="scholarship_id" id="scholarship_id" class="w-100">
                                        <option value="" selected>SELECT SCHOLARSHIP</option>
                                        @foreach ($data['scholarships'] as $scholarship)
                                            <option value="{{ $scholarship['id']  }}">
                                                {{ strtoupper($scholarship['description']) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                        <div class="row px-3">
                            @if (count($data['colleges']) > 0)
                                <div class="col-sm-12 px-0">
                                    <label for="college_id">COLLEGES</label>
                                    <select name="college_id" id="college_id" class="w-100">
                                        <option value="" selected>SELECT COLLEGE</option>
                                        @foreach ($data['colleges'] as $college)
                                            <option value="{{ $college['id']  }}">
                                                {{ strtoupper($college['college_name']) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                                <div class="col-sm-12 px-0" id="course-container">
                                    <label for="course_id">COURSE</label>
                                    <input type="text" class="form-control p-0 pt-1" readonly>
                                </div>


                                <div class="col-sm-4 px-0">
                                    @php
                                        $num = 0;
                                    @endphp
                                    <label for="status">STATUS</label>
                                    <select name="status" id="status" class="w-100">
                                        <option value="" selected>SELECT STATUS</option>
                                        @foreach ($data['count'] as $key => $value)
                                            <option value="{{ $key }}">
                                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <br />
                                    <button type="submit" class="btn btn-success p-0 px-4">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                @if (count($data['applications']) > 0)
                    <small>
                        <span class="badge alert alert-secondary px-5 w-100 p-2">
                            Showing {{ count($data['applications']) }} of {{ $data['applications']->total() }} entries
                        </span>
                    </small>
                    <br />
                    <div class="row">
                        <div class="col-sm-12">
                            <a href="{{ str_replace('/list', '', $data['pdf']) }}" target="_blank" class="btn btn-secondary btn-sm p-1 px-3">
                                <i class="fa fa-download"></i> PDF
                            </a>
                            <a href="{{ str_replace('/list', '', $data['excel']) }}" target="_blank" class="btn btn-secondary btn-sm p-1 px-3">
                                <i class="fa fa-list-alt"></i> EXCEL
                            </a>
                        </div>
                    </div>
                    <br />
                    <div class="table-container pt-3">

                    <small>
                        {{ $data['applications']->render() }}
                    </small>
                    <table class="table table-bordered table-hover table-condensed table-striped bg-white" style="font-size:11px !important;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Course,&nbsp;Year&nbsp;&amp;&nbsp;Section</th>
                                <th>College</th>
                                <th>Scholarship</th>
                                <th>S.Y.</th>
                                <th>Application&nbsp;date/time</th>
                                <th>Updated&nbsp;at</th>
                                <th><center>Status</center></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($data['applications'] as $application)
                            @php
                                $user = $application['users'];
                                $sy = $application['school_years'];
                                $offer = $application['scholarship_offers'];
                            @endphp
                                <tr>
                                    <td class="capitalize">{{ $user['last_name'] ?? '' }}, {{ $user['first_name'] ?? '' }} {{ $user['middle_name'] ?? '' }} {{ $user['name_extension'] ?? '' }}</td>
                                    <td>
                                        @if (!empty($data['detail'][$application['user_id']]))
                                            @php
                                                $detail = $data['detail'][$application['user_id']];
                                                $course = $data['detail'][$application['user_id']]['courses'];
                                            @endphp
                                        @endif

                                        {{ $course['course_code'] ?? "" }} {{ $course['year_level'] ?? '' }} - {{ $course['section'] ?? '' }}
                                    </td>
                                    <td style="text-transform:capitalize !important;">
                                        ({{ $application['details']['courses']['colleges']['college_code'] ?? '' }})
                                        {{ $application['details']['courses']['colleges']['college_name'] ?? '' }}
                                    </td>
                                    <td class="capitalize">{{ $offer['scholarships']['description'] }}</td>
                                    <td>
                                        {{ $sy['semester'] }}
                                        @if ($sy['semester'] <> 'Summer')
                                            Semester
                                        @endif
                                        {{ $sy['start_year'] }} - {{ $sy['end_year'] }}
                                    </td>
                                    <td>
                                        @if (strtotime($application['date_completed']) !== false)
                                            {{ now()->parse($application['date_completed'])->format('M, j Y h:i a') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (strtotime($application['date_updated']) !== false)
                                            {{ now()->parse($application['date_updated'])->format('M, j Y h:i a') }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!is_null($application['under_review']))
                                            @if ($application['under_review'])
                                                <span class="badge bg-primary">reviewed</span>
                                            @else
                                                <span class="badge bg-info">under review</span>
                                            @endif
                                        @endif

                                        @if (is_null($application['approved']))
                                            @if (!is_null($application['request_to_change']))
                                                @if ($application['request_to_change'])
                                                    <span class="badge bg-info">requested changes</span>
                                                @else
                                                    <span class="badge bg-info">updated</span>
                                                @endif
                                            @endif
                                        @endif

                                        @if (is_null($application['approved']))
                                            <span class="badge bg-warning">for approval</span>
                                        @else
                                            @if ($application['approved'])
                                                <span class="badge bg-success">approved</span>
                                            @else
                                                <span class="badge bg-danger">denied</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data['applications']->render() }}
                @else
                    @include('layouts.partials._no-record')
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function() {
        $('#sy_id, #scholarship_id, #status, #college_id').select2({
            width: 'resolve'
        });

        @if (!empty($data['sy_id']))
            $('#sy_id').val("{{ $data['sy_id'] }}").trigger('change');
        @endif

        @if (!empty($data['scholarship_id']))
            $('#scholarship_id').val("{{ $data['scholarship_id'] }}").trigger('change');
        @endif

        @if (!empty($data['status']))
            $('#status').val("{{ $data['status'] }}").trigger('change');
        @endif

        @if (!empty($data['college_id']))
            $('#college_id').val("{{ $data['college_id'] }}").trigger('change');

            setTimeout(function(){
                $('#college_id').trigger('change');
            }, 1000);
        @endif

        $('#college_id').on('change', function(){
            var college_id = $(this).val();

            $.ajax({
                url:"{{ route('courses-load') }}",
                method:'POST',
                data:{
                    college_id:college_id
                },
                dataType:'json',
                success:function(response){
                    if(response.empty) {
                        return;
                    }

                    $('#course-container').html(response.html);

                    @if (!empty($data['course_id']))
                        setTimeout(function(){
                            $('#course_id').val("{{ $data['course_id'] }}").trigger('change');
                        }, 1000);
                    @endif
                }
            });
        });
    });
</script>
@endsection
