@extends('layouts.plain')
@section('content')
<div style="background:#006400 !important;padding:10px 0;">
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents('https://isu.edu.ph/wp-content/uploads/2020/02/isu_banner_rv2-1.png')) }}" style="width:50%;">
</div>
<br />
<div class="row">
    <div class="col-sm-12 p-0">
        List of Applications
        @if (!empty($data['scholarship']))
                for <b>{{ strtoupper($data['scholarship']['description']) }}</b>
        @endif
        @if (!empty($data['sy']))
            for the <b>{{ $data['sy']['semester'] }} {{ $data['sy']['semester'] <> 'Summer' ? 'Semester' : '' }} of S.Y. {{ $data['sy']['start_year'] }}-{{ $data['sy']['end_year'] }}</b>
        @endif
        <br /><br />

            @if (count($data['applications']) > 0)
                <table border="1" cellpadding="1" cellspacing="0" id="example" style="width:100%;font-size:11px;">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Course,&nbsp;Year&nbsp;&amp;&nbsp;Section</th>
                            <th>College</th>
                            <th>Scholarship</th>
                            <th>S.Y.</th>
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
                                <td style="text-transform:capitalize;">{{ $user['last_name'] ?? '' }}, {{ $user['first_name'] ?? '' }} {{ $user['middle_name'] ?? '' }} {{ $user['name_extension'] ?? '' }}</td>
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
                                    {{ $application['details']['courses']['colleges']['college_code'] ?? '' }}
                                </td>
                                <td style="text-transform:capitalize;">{{ $offer['scholarships']['description'] }}</td>
                                <td>{{ $sy['semester'] }} {{ $sy['semester'] <> 'Summer' ? 'Semester' : '' }} {{ $sy['start_year'] }} - {{ $sy['end_year'] }}</td>
                                <td class="text-center">
                                    @if (is_null($application['approved']))
                                        for approval
                                    @else
                                        @if ($application['approved'])
                                            approved
                                        @else
                                            denied
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
    </div>
</div>

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

<script>
    new DataTable('#example', {
        ordering:false,
        info:false,
        paging:false,
        searching:false,
        layout: {
            topStart: {
                buttons: ['excel',]
            }
        }
    });
</script>
@endsection
