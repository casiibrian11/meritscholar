@extends('layouts.app')

@section('content')
<style>
    body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }


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
        .uppercase{
            text-transform:uppercase;
        }
</style>
<img src="{{ asset('css/logo5.png') }}" style="width:100%;">
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="row mt-2 justify-content-center">
                <div class="col-sm-7 pt-0 p-4 border rounded-3" style="background:#006400 !important;color:#FFF !important;">
                <form action="{{ route('personal-information-save') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h2 class="border-bottom border-white p-0 pb-2"><b>Mailing Address</b></h2>
                    <input type="hidden" name="step" value="2" readonly>
                    <input type="hidden" name="id" value="{{ $info['id'] ?? '' }}" readonly>

                    <!-- <label>LEARNERS REFERENCE NUMBER:</label>
                    <input type="text" name="learner_reference_number" required>
                    <br>

                    <label>ATM ACCOUNT NUMBER:</label>
                    <input type="text" name="atm_account_number" required>
                    <br>

                    <label>STUDENT ID/NUMBER:</label>
                    <input type="text" name="student_id" required>
                    <br> -->

                    <label>REGION:</label>
                    <input type="text" class="uppercase" name="region" value="{{ $info['region'] ?? '' }}" required>
                    <br>

                    <label>PROVINCE:</label>
                    <input type="text" class="uppercase" name="province" value="{{ $info['province'] ?? '' }}" required>
                    <br>

                    <label>CITY / MUNICIPALITY:</label>
                    <input type="text" class="uppercase" name="municipality" value="{{ $info['municipality'] ?? '' }}" required>
                    <br>

                    <label>BARANGAY:</label>
                    <input type="text" class="uppercase" name="barangay" value="{{ $info['barangay'] ?? '' }}" required>
                    <br>

                    <label>PUROK | STREET | HOUSE # | BUILDING | SUBDIVISION:</label>
                    <input type="text" class="uppercase" name="address_line" value="{{ $info['address_line'] ?? '' }}" required>

                    <div class="row">
                        <div class="col-sm-6">
                            <a href="/personal-information">
                                <button type="button" class="btn btn-secondary w-100">
                                    <b>GO BACK</b>
                                </button>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary w-100">
                                <b>NEXT</b>
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
