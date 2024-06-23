<div style="background:#006400 !important;padding:10px 0;">
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents('https://isu.edu.ph/wp-content/uploads/2020/02/isu_banner_rv2-1.png')) }}" style="width:50%;">
</div>
<br />
<div class="row">
    <div class="col-sm-12 p-0">
        <div class="card p-0 main-body">
            List of Applications
            @if (!empty($data['scholarship']))
                 for <b>{{ strtoupper($data['scholarship']['description']) }}</b>
            @endif
            @if (!empty($data['sy']))
                for the <b>{{ $data['sy']['semester'] }} {{ $data['sy']['semester'] <> 'Summer' ? 'Semester' : '' }} of S.Y. {{ $data['sy']['start_year'] }}-{{ $data['sy']['end_year'] }}</b>
            @endif
            <br /><br />

                @if (count($data['applications']) > 0)
                    <table border="1" cellpadding="1" cellspacing="0" style="width:100%;font-size:11px;">
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
                                    <td>
                                        {{ $sy['semester'] }}
                                        @if ($sy['semester'] <> 'Summer')
                                            Semester
                                        @endif
                                        {{ $sy['start_year'] }} - {{ $sy['end_year'] }}
                                    </td>
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

                <br />
                <br />
            <p>
                Prepared by: <br /><br />
                <b>{{ ucwords(Auth::user()->first_name) }} {{ ucwords(Auth::user()->first_name) }}</b><br />
                <small>{{ now()->format('F j, Y h:i a') }}</small>
            </p>
        </div>
    </div>
</div>
