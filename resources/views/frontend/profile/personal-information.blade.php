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
</style>
<img src="{{ asset('css/logo5.png') }}" style="width:100%;">
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="row mt-2 justify-content-center">
                <div class="col-sm-7 pt-0 p-4 border rounded-3" style="background:#006400 !important;color:#FFF !important;">
                <form action="{{ route('personal-information-save') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h2 class="border-bottom border-white p-0 pb-2"><b>Personal Information</b></h2>
                    <input type="hidden" name="step" value="1" readonly>

                    <label>SEX AT BIRTH:</label>
                    <select name="sex" id="sex" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="prefer not to say">I prefer not to say</option>
                    </select>
                    <br>

                    <label>GENDER IDENTIFICATION:</label>
                    <select name="gender" id="gender">
                        <option value="n/a" selected>n/a</option>
                        <option value="Prefer not to say">I prefer not to say</option>
                        <option value="Lesbian">Lesbian</option>
                        <option value="Gay">Gay</option>
                        <option value="Bisexual">Bisexual</option>
                        <option value="Transgender">Transgender</option>
                        <option value="Nonbinary">Nonbinary</option>
                        <option value="Genderqueer">Genderqueer</option>
                        <option value="Cisgender">Cisgender</option>
                        <option value="Androgyne">Androgyne</option>
                        <option value="Intersex">Intersex</option>
                        <option value="Omnigender">Omnigender</option>
                        <option value="Two-spirit">Two-spirit</option>
                        <option value="Agender">Agender</option>
                        <option value="FTM">FTM</option>
                        <option value="Butch">Butch</option>
                        <option value="Pansexual">Pansexual</option>
                        <option value="None of the above">None of the above</option>
                    </select>
                    <br>

                    <label>BIRTHDATE (mm/dd/yyyy):</label>
                    <input type="date" name="birthdate" value="{{ $info['birthdate'] ?? '' }}" required>
                    <br>

                    <label>CONTACT NUMBER:</label>
                    <input type="text" name="contact_number" value="{{ $info['contact_number'] ?? '' }}" required>
                    <br><br><br>

                    <label>MONTHLY INCOME OF YOUR PARENTS:</label>
                    <input type="radio" name="monthly_income" value="Greater than or equal to P219,140" required>Greater than or equal to P219,140<br>
                    <input type="radio" name="monthly_income" value="Greater than or equal to P131,484 but less than P219,140">Greater than or equal to P131,484 but less than P219,140<br>
                    <input type="radio" name="monthly_income" value="Greater than or equal to P76,669 but less than P131,484">Greater than or equal to P76,669 but less than P131,484<br>
                    <input type="radio" name="monthly_income" value="Greater than or equal to P43,828 but less than P76,669">Greater than or equal to P43,828 but less than P76,669<br>
                    <input type="radio" name="monthly_income" value="Greater than or equal to P21,194 but less than P43,828">Greater than or equal to P21,194 but less than P43,828<br>
                    <input type="radio" name="monthly_income" value="Greater than or equal to P10,957 but less than P21,194">Greater than or equal to P10,957 but less than P21,194<br>
                    <input type="radio" name="monthly_income" value="Less than P10,957">Less than P10,957<br><br>


                    <!-- Add other radio button groups similarly -->

                    <label>DEPENDENT/SOLO PARENT:</label>
                    <input type="radio" name="parent_status" value="dependent" required>Dependent<br>
                    <input type="radio" name="parent_status" value="solo_parent">Solo Parent<br><br>

                    <!-- Add other radio button groups similarly -->

                    <label>Do you have any disability?</label>
                    <input type="radio" name="disability" value="yes" required>Yes<br>
                    <input type="radio" name="disability" value="no">No<br><br>

                    <!-- Add other radio button groups similarly -->

                    <label>Have you ever undergone any kinds of operation / procedure?</label>
                    <input type="radio" name="operations" value="yes" required>Yes<br>
                    <input type="radio" name="operations" value="no">No<br><br>

                    <label>Do you belong to Indigenous People and Ethnic Group?</label>
                    <input type="text" name="indigenous_group" value="{{ $info['indigenous_group'] ?? '' }}">
                    <br>

                    <label>Your Facebook Link:</label>
                    <input type="text" name="facebook_link" value="{{ $info['facebook_link'] ?? '' }}">
                    <br>

                    <p class="text-right" style="text-align:right;">
                        <button type="submit" class="btn btn-primary w-50">
                            <b>NEXT</b>
                        </button>
                    </p>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        @if(!empty($info))
            $("input[name='monthly_income'][value='{{ $info["monthly_income"] }}']").prop('checked', true);
            $("input[name='parent_status'][value='{{ $info["parent_status"] }}']").prop('checked', true); 
            $("input[name='disability'][value='{{ $info["disability"] }}']").prop('checked', true);
            $("input[name='operations'][value='{{ $info["operations"] }}']").prop('checked', true); 

            $('#sex').val('{{ $info["sex"] ?? "" }}');
            $('#gender').val('{{ $info["gender"] ?? "" }}');
        @endif
    });
</script>
@endsection
