@extends('layouts.app')

@section('content')
<img src="{{ asset('css/logo5.png') }}" style="width:100%;">
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-success">
                <center>
                    Hello, <b>{{ ucwords(Auth::user()->first_name) }} {{ ucwords(Auth::user()->last_name) }}</b>!
                </center>
            </div>
        </div>
    </div>
</div>
@endsection
