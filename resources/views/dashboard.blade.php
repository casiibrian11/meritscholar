@extends('layouts.admin')

@section('content')
<style>
    .fixed{
        position:fixed;
        top:0;
        right:0;
        left:0;
        z-index:1000000 !important;
        padding:5px !important;
    }
</style>
<h1 class="mt-2 p-0"><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
<div class="row">
    <div class="col-sm-12 p-0">

        <div class="alert bg-light shadow filters">
            <div class="btn-group" role="group" aria-label="Active">
                @foreach ($data['school_years'] as $sy)
                    <a href="?sy_id={{ $sy['id'] }}" type="button" class="btn btn-sm border-dark @if (!empty($data['sy_id']) && $data['sy_id'] == $sy['id']) btn-success @else btn-light @endif">
                        {{ $sy['semester'] }} {{ $sy['semester'] <> 'Summer' ? 'Semester' : '' }} of S.Y. {{ $sy['start_year'] }}-{{ $sy['end_year'] }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="col-sm-12">
            <div class="row justify-content-center">
                <div class="col-sm-5">
                    <center>
                        <h4>
                            <b>SEX AT BIRTH CHART</b>
                        </h4>
                    </center>
                    <canvas id="sexChart"></canvas>
                </div>
                <div class="col-sm-5">
                    <center>
                        <h4>
                            <b>GENDER IDENTIFICATION CHART</b>
                        </h4>
                    </center>
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        
            <div class="row">
                <div class="col-sm-6">
                    <div class="col-sm-12 shadow p-3 rounded-5 mt-4 p-0" style="border:1px solid #006400;">
                        <h4>
                            <b><i class="fas fa-tachometer-alt"></i> APPLICATIONS</b>
                        </h4>
                        <canvas id="applicationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var genderChart = document.getElementById('genderChart').getContext('2d');
    var sexChart = document.getElementById('sexChart').getContext('2d');
    var applicationsChart = document.getElementById('applicationsChart').getContext('2d');

    var genderChart = new Chart(genderChart, {
        type: 'pie',
        data: {
            labels: @json($data['genderLabels']),
            datasets: [{
                data: @json($data['genderCounts'])
            }]
        },
    });

    var sexChart = new Chart(sexChart, {
        type: 'pie',
        data: {
            labels: @json($data['sexLabels']),
            datasets: [{
                data: @json($data['sexCounts'])
            }]
        },
    });

    var applicationsChart = new Chart(applicationsChart, {
        type: 'line',
        data: {
            labels: @json($data['applicationsLabels']),
            datasets: [{
                label: '',
                data: @json($data['applicationsCounts']),
                borderColor: '#006400',
                borderWidth: 1,
                fill: false,
                backgroundColor: '#006400'
            }],
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        },
    });

    $(function(){
        var distance = $('.filters').offset().top;

        $(window).scroll(function () {
            if ($(window).scrollTop() >= distance) {
                $('.filters').addClass("fixed");

            } else {
                $('.filters').removeClass("fixed");
            }
        });
    });
</script>
@endsection