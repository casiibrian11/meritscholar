@extends('layouts.admin')

@section('content')
<h1 class="mt-2 p-0"><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
<div class="row">
    <div class="col-sm-12 p-0">
        <div class="row justify-content-center">
            <div class="col-sm-5">
                <center>
                    <h4>
                        <b>SEX CHART</b>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var genderChart = document.getElementById('genderChart').getContext('2d');
    var sexChart = document.getElementById('sexChart').getContext('2d');

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
</script>
@endsection