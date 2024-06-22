@extends('layouts.app')

@section('content')
<style>
    .navbar{
        display:none !important;
    }
</style>
<a href="/home">
<img src="{{ asset('css/logo5.png') }}" style="width:100%;">
</a>

<div class="row justify-content-center">
    <div class="col-sm-8 mt-5 shadow rounded-4 p-3">
        <div class="alert bg-danger text-white">
            <center>
                <b><i class="fa fa-exclamation-circle"></i> YOUR ACCOUNT IS DISABLED!</b>
            </center>
        </div>

        <div class="alert alert-danger">
            This account has been disabled. Please ask the administrator for help regarding this account.
            @if (!empty(Auth::user()->disabled_by))
                <div class="alert alert-light mt-3 small">
                Reasons for account being disabled.
                <ul>
                    @if (Auth::user()->disabled_by == 'system')
                    <li>{{ ucfirst(Auth::user()->disabled_note) }}</li>
                    @endif
                    <li>Disabled by <b>{{ Auth::user()->disabled_by }}</b></li>
                </ul>
                </div>
            @endif

            <a class="btn btn-secondary btn-sm"
                href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"><i class="fa fa-home"></i> RETURN HOME</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>
@endsection