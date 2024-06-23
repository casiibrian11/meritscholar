@extends('layouts.admin')

@section('content')
<style>
    .selected {
        border-bottom: 3px solid #555 !important;
    }
    .badge{
        color:#000 !important;
    }
</style>
<h3 class="mt-2 p-0"><i class="fa fa-edit"></i> List of Applications</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item active">List of Applications</li>
</ol>
<div class="row">
    <div class="col-sm-12 p-0">
        <div class="card p-0 main-body">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-12">
                    <form action="" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control border-dark" placeholder="Search (name or email)..." name="keyword" 
                                value="@if (!empty($data['keyword'])){{ $data['keyword'] }}@endif">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i> Search</button>
                            </div>
                        </div>
                    </form>
                    @if (count($data['school_years']) > 0)
                        <div class="btn-group mb-2 flex-wrap" role="group" aria-label="Active">
                            <a href="/scholarship/applications" 
                                type="button" 
                                class="btn btn-sm btn-outline-secondary @if (empty($data['sy_id'])) bg-secondary text-white @endif">
                                CLEAR FILTERS
                            </a>
                            @foreach ($data['school_years'] as $sy)
                                <a href="?sy_id={{ $sy['id'] }}" 
                                    type="button" 
                                    class="btn btn-sm btn-outline-secondary @if (!empty($data['sy_id']) && $data['sy_id'] == $sy['id']) bg-secondary text-white @endif">
                                    {{ $sy['semester'] }}
                                    @if ($sy['semester'] <> 'Summer')
                                        Semester
                                    @endif
                                    of
                                    {{ $sy['start_year'] }} - {{ $sy['end_year'] }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if (count($data['scholarships']) > 0)
                        <div class="btn-group mb-2 flex-wrap" role="group" aria-label="Active">
                            @foreach ($data['scholarships'] as $scholarship)
                                <a href="?sy_id={{ $data['sy_id'] ?? '' }}&scholarship_id={{ $scholarship['id']  }}" 
                                    type="button" 
                                    class="btn btn-sm btn-outline-secondary @if (!empty($data['scholarship_id']) && $data['scholarship_id'] == $scholarship['id']) bg-secondary text-white @endif">
                                    {{ strtoupper($scholarship['description']) }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                    </div>

                    @php
                        $num = 0;
                    @endphp
                    @foreach ($data['count'] as $key => $value)
                        @php
                            $num += 1;
                            $bg = [
                                '1' => 'bg-info',
                                '2' => 'bg-primary',
                                '3' => 'bg-success',
                                '4' => 'bg-danger',
                            ];
                        @endphp
                            <div class="col-sm-3 p-0">
                                <a href="?sy_id={{ $data['sy_id'] ?? '' }}&scholarship_id={{ $data['scholarship_id'] ?? '' }}&status={{ $key }}" style="text-decoration:none;">
                                <div class="container p-1 text-light {{ $bg[$num] }} border rounded-4 text-shadow shadow
                                    @if (!empty($data['status']) && $data['status'] == $key) py-2 selected @endif">
                                    <center>
                                        {{ ucwords(str_replace('_', ' ', $key)) }} <br />
                                        <h4 class="p-0 m-0">
                                            <b>
                                            {{ $value }}
                                            </b>
                                        </h3>
                                    </center>
                                </div>
                                </a>
                            </div>
                    @endforeach
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
                            <a href="{{ $data['pdf'] }}" target="_blank" class="btn btn-secondary btn-sm p-1 px-3">
                                <i class="fa fa-download"></i> PDF
                            </a>
                            <a href="{{ $data['excel'] }}" target="_blank" class="btn btn-secondary btn-sm p-1 px-3">
                                <i class="fa fa-list-alt"></i> EXCEL
                            </a>
                        </div>
                    </div>
                    <br />
                    <div class="table-container">
                    <small>
                        {{ $data['applications']->render() }}
                    </small>
                    <table class="table table-bordered table-hover table-condensed table-striped" style="font-size:11px !important;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Course,&nbsp;Year&nbsp;&amp;&nbsp;Section</th>
                                <th>College</th>
                                <th>Scholarship</th>
                                <th>S.Y.</th>
                                <th><center>Status</center></th>
                                <th class="text-center">Created</th>
                                <th class="text-center">Updated</th>
                                <th></th>
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
                                    <td>
                                        {{ $application['details']['courses']['colleges']['college_code'] ?? '' }}
                                    </td>
                                    <td class="capitalize">{{ $offer['scholarships']['description'] }}</td>
                                    <td>
                                        {{ $sy['semester'] }}
                                        @if ($sy['semester'] <> 'Summer')
                                            Semester
                                        @endif
                                        {{ $sy['start_year'] }} - {{ $sy['end_year'] }}
                                    </td>
                                    <td class="text-center">
                                        @if (is_null($application['approved']))
                                            @if ($application['completed'])
                                                <span class="badge bg-success">completed</span>
                                            @endif
                                        @endif
                                        
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
                                    <td class="text-center">
                                        @if (!empty($application['date_completed']) && strtotime($application['date_completed']) !== false)
                                            <span class="badge bg-light border border-dark text-dark">
                                                {{ now()->parse($application['date_completed'])->format('M j, Y') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($application['date_updated']) && strtotime($application['date_updated']) !== false)
                                            <span class="badge bg-light border border-dark text-dark">
                                                {{ now()->parse($application['date_updated'])->format('M j, Y') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="/scholarship/applications/{{ $application['id'] }}/requirements" class="btn btn-outline-secondary w-100 p-0 px-2"
                                         style="font-size:12px;">
                                            @if (is_null($application['approved']))
                                                <span class="fa fa-folder-open"></span>&nbsp;Check&nbsp;Requirements
                                            @else
                                                <span class="fa fa-folder-open"></span>&nbsp;View&nbsp;Application
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br />
                    {{ $data['applications']->render() }}
                @else
                    @include('layouts.partials._no-record')
                @endif
            </div>
        </div>
    </div>
    <br />
<br />
<br />
<br />
<br />
</div>

<script>
    $(function(){
    })
</script>
@endsection
