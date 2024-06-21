@extends('layouts.app')

@section('content')
<style>
    h2 {
        text-align: center;
        color: white;
    }

    form {
        max-width: 100%;
        margin-top: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    input[type="checkbox"],
    input[type="radio"] {
        margin-right: 5px;
    }

    input[type="file"] {
        margin-top: 5px;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }
    h1 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }
    p {
        color: #666;
        line-height: 1.6;
    }
    a {
        color: #007bff;
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
</style>
<img src="{{ asset('css/logo5.png') }}" style="width:100%;">
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="row mt-2 justify-content-center">
                <div class="col-sm-6 pt-0 p-4 border rounded-3" style="background:#006400 !important;color:#FFF !important;">
                <form action="{{ route('account-update') }}" method="POST">
                    @csrf
                    <h2 class="border-bottom border-white p-0 pb-2"><b>Account Information</b></h2>
                    <label>LAST NAME:</label>
                    <input type="text" name="last_name" value="{{ Auth::user()->last_name }}" required>

                    <label>FIRST NAME:</label>
                    <input type="text" name="first_name" value="{{ Auth::user()->first_name }}" required>

                    <label>MIDDLE NAME:</label>
                    <input type="text" name="middle_name" value="{{ Auth::user()->middle_name }}" required>

                    <label>NAME EXTENSION:</label>
                    <input type="text" name="name_extension" value="{{ Auth::user()->name_extension }}">

                    <p class="text-right" style="text-align:right;">
                        <button type="submit" class="btn btn-primary w-50">
                            <b>SUBMIT</b>
                        </button>
                    </p>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
