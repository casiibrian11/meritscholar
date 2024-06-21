@extends('layouts.app')

@section('content')
<img src="{{ asset('css/logo5.png') }}" style="width:100%;">
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="p-0"><b>{{ __('We sent a verification code to your email!') }}</b></h5>
                </div>

                <div class="card-body">
                    <div class="alert alert-info" style="font-size:12px;">
                        <ul class="p-0 m-0 px-2">
                            <li>If you did not receive any email to your inbox, try checking your spam folder.</li>
                            <li>If you still haven't received anything after a few minutes, try resending a new verification code.</li>
                        </ul>
                    </div>
                    <form method="POST" action="{{ route('verify') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="verification_code" class="col-md-4 col-form-label text-md-end">{{ __('Verification code:') }}</label>

                            <div class="col-md-6">
                                <input id="verification_code" type="text" class="form-control" name="verification_code" required autocomplete="off" autofocus>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>
                                &nbsp;
                                <span id="resendBtn"></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let timer;
let countdown = 120;

function startResendTimer() {
    // Start the countdown
    timer = setInterval(updateTimer, 1000);
}

function updateTimer() {
    const timerElement = $('#resendBtn');
    
    if (countdown > 0) {
        timerElement.html(`Resend in ${countdown} seconds`);
        countdown--;
    } else {
        var resendBtn = '<a href="/send-verification" class="btn btn-info">Resend Code</a>';
        
        // Reset countdown for the next attempt
        countdown = 60;
        
        // Stop the timer
        clearInterval(timer);
        timerElement.html(resendBtn);
    }
}
$(function(){
    startResendTimer();
});
</script>
@endsection
