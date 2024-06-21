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
</style>
<img src="{{ asset('css/logo5.png') }}" style="width:100%;">
<div class="container mt-3">
    @if (count($data) > 0)
        <div class="row justify-content-center">
            <div class="col-sm-12">
            <center>
                <h5>
                    <b>Select from options below to view offerings</b>
                </h5>
            </center>
            @foreach($data as $row)
                    <div class="col-sm-6 border rounded-4 shadow p-4 alert alert-success m-auto mb-3 border border-dark">
                        <a href="/scholarships/view/{{ $row['id'] }}/list">
                            <b>
                            <center>
                                @if ($row['semester'] == '1st')
                                    First Semester
                                @elseif ($row['semester'] == '2nd')
                                    Second Semester
                                @else
                                    Summer
                                @endif
                                of S.Y. {{ $row['start_year'] }} - {{ $row['end_year'] }}
                            </center>
                            </b>
                        </a>
                    </div>
            @endforeach
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
</div>
@endsection
