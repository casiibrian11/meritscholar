@extends('layouts.app')

@section('content')
<img src="{{ asset('css/logo5.png') }}" style="width:100%;">
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <center><h4 class="p-0"><b>{{ __('Register Account') }}</b></h4></center>
                </div>
                <div class="card-body">
                    @include('layouts.required')

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row mb-1">
                            <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('First name') }} <span class="required">*</span></label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" 
                                    class="form-control @error('first_name') is-invalid @enderror" 
                                    name="first_name" value="{{ old('first_name') }}" 
                                    required autocomplete="off" autofocus
                                    placeholder="First name...">

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-1">
                            <label for="middle_name" class="col-md-4 col-form-label text-md-end">{{ __('Middle name') }} <span class="required">*</span></label>

                            <div class="col-md-6">
                                <input id="middle_name" type="text" 
                                    class="form-control @error('middle_name') is-invalid @enderror" 
                                    name="middle_name" value="{{ old('middle_name') }}" 
                                    required autocomplete="off" autofocus
                                    placeholder="Middle name...">

                                @error('middle_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ __('Last name') }} <span class="required">*</span></label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" 
                                    class="form-control @error('last_name') is-invalid @enderror" 
                                    name="last_name" value="{{ old('last_name') }}" 
                                    required autocomplete="off" autofocus
                                    placeholder="Last name...">

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-5">
                            <label for="name_extension" class="col-md-4 col-form-label text-md-end">{{ __('Name extension') }}</label>

                            <div class="col-md-6">
                                <input id="name_extension" type="text" 
                                    class="form-control @error('name_extension') is-invalid @enderror" 
                                    name="name_extension" value="{{ old('name_extension') }}" 
                                    autocomplete="off" autofocus
                                    placeholder="Name extension (optional)...">

                                @error('name_extension')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }} <span class="required">*</span></label>

                            <div class="col-md-6">
                                <input id="email" type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" 
                                    required autocomplete="email"
                                    placeholder="Email address (requires verification)...">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }} <span class="required">*</span></label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }} <span class="required">*</span></label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" id="register" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#register').attr('disabled', true);
        $('#email').on('focus', function(){
            $('#register').attr('disabled', true);
        });

        $('#email').on('blur', function(){
            var email = $(this).val();


            if (email == "") {
                return;
            }

            $.ajax({
                url:"{{route('verify-email')}}",
                type:'POST',
                data: {
                    email:email
                },
                dataType:'json',
                beforeSend:function(){
                    loader();
                },
                success:function(response){
                    loaderx();
                    if (response.error) {
                        customAlert('error', response.error);
                        $('#register').attr('disabled', true);
                        $('#email').val('');
                    } else {
                        $('#register').attr('disabled', false);
                    }
                },
                error:function(data){
                    loaderx();
                    console.log(data);
                    var message = "";
                    var errors = data.responseJSON;
                    $.each( errors.errors, function(key, value) {
                        message += '<li>'+ value +'</li>';
                    });
                    customAlert('error', message);
                }   
            });
        });
    });
</script>
@endsection
