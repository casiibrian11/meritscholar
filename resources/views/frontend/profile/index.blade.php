@extends('layouts.app')

@section('content')
<style>
    .w3-table tr td b{
        text-transform:uppercase;
    }
</style>
<img src="{{ asset('css/logo5.png') }}" style="width:100%;">
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="row mt-2 justify-content-center">
                <div class="col-sm-10 px-4">
                    <p style="text-align:right;">
                        <a href="/personal-information" class="btn btn-outline-secondary py-1" style="font-size:11px;">
                            <i class="fa fa-edit"></i> <b>Update information</b>
                        </a>
                        <a href="/account" class="btn btn-outline-secondary py-1" style="font-size:11px;">
                            <i class="fa fa-lock"></i> <b>Update account</b>
                        </a>

                        @if (Route::has('password.request'))
                            <a class="btn btn-outline-secondary py-1" style="font-size:11px;" href="{{ route('password.request') }}">
                                <i class="fa fa-lock"></i> <b>Reset password</b>
                            </a>
                        @endif
                    </p>
                    <div class="row mt-2">
                        <h4 class="border-bottom border-secondary pb-2">
                            <b>
                                Personal Information
                            </b>
                        </h4>
                        <div class="col-sm-6">
                            <table class="w3-table text-xs">
                                <tr>
                                    <td><p></p></td>
                                </tr>
                                <tr>
                                    <td>Complete Name</td>
                                </tr>
                                <tr>
                                    <td><b>{{ Auth::user()->first_name }} {{ Auth::user()->middle_name }} {{ Auth::user()->last_name }} {{ Auth::user()->name_extension }}</b></td>
                                </tr>
                                <tr>
                                    <td><p></p></td>
                                </tr>
                                <tr>
                                    <td>Sex at birth</td>
                                </tr>
                                <tr>
                                    <td><b>{{ $info['sex'] ?? '' }}</b></td>
                                </tr>
                                <tr>
                                    <td><p></p></td>
                                </tr>
                                <tr>
                                    <td>Gender Identification</td>
                                </tr>
                                <tr>
                                    <td><b>{{ $info['gender'] ?? '' }}</b></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-6">
                            <table class="w3-table text-xs">
                                @if (strtotime($info['birthdate']) !== false)
                                    <tr>
                                        <td><p></p></td>
                                    </tr>
                                    <tr>
                                        <td>Birthdate</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>
                                                {{ now()->parse($info['birthdate'])->format('F j, Y') }}
                                            </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><p></p></td>
                                    </tr>
                                    <tr>
                                        <td>Age</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>
                                                {{ now()->diffInYears($info['birthdate']) }}
                                            </b>
                                        </td>
                                    </tr>
                                    
                                @endif
                            </table>
                        </div>
                        <div class="col-sm-12 mt-4">
                            <table class="w3-table text-xs">
                                <tr>
                                    <td><p></p></td>
                                </tr>
                                <tr>
                                    <td>Parent's monthly income</td>
                                </tr>
                                <tr>
                                    <td><b style="text-transform:none !important;">{{ $info['monthly_income'] ?? '' }}</b></td>
                                </tr>
                                <tr>
                                    <td><p></p></td>
                                </tr>
                                <tr>
                                    <td>Parent's status</td>
                                </tr>
                                <tr>
                                    <td><b style="text-transform:none !important;">{{ ucwords(str_replace('_',' ', $info['parent_status'])) ?? '' }}</b></td>
                                </tr>
                                <tr>
                                    <td><p></p></td>
                                </tr>
                                <tr>
                                    <td>Do you have any disability?</td>
                                </tr>
                                <tr>
                                    <td><b>{{ $info['disability'] ?? '' }}</b></td>
                                </tr>
                                <tr>
                                    <td><p></p></td>
                                </tr>
                                <tr>
                                    <td>Have you ever undergone any kinds of operation / procedure?</td>
                                </tr>
                                <tr>
                                    <td><b>{{ $info['operations'] ?? '' }}</b></td>
                                </tr>
                                <tr>
                                    <td><p></p></td>
                                </tr>
                                <tr>
                                    <td>Do you belong to Indigenous People and Ethnic Group?</td>
                                </tr>
                                <tr>
                                    <td><b>{{ $info['indigenous_group'] ?? '' }}</b></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row my-5">
                        <h4 class="border-bottom border-secondary pb-2">
                            <b>
                                Contact Information | Address
                            </b>
                        </h4>
                        <div class="row">
                            <div class="col-sm-4">
                                <table class="w3-table text-xs">
                                    <tr>
                                        <td><p></p></td>
                                    </tr>
                                    <tr>
                                        <td>Email address</td>
                                    </tr>
                                    <tr>
                                        <td><b style="text-transform:none !important;">{{ Auth::user()->email }}</b></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <table class="w3-table text-xs">
                                    <tr>
                                        <td><p></p></td>
                                    </tr>
                                    <tr>
                                        <td>Contact #</td>
                                    </tr>
                                    <tr>
                                        <td><b style="text-transform:none !important;">{{ $info['contact_number'] ?? '' }}</b></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <table class="w3-table text-xs">
                                    <tr>
                                        <td><p></p></td>
                                    </tr>
                                    <tr>
                                        <td>Facebook Link</td>
                                    </tr>
                                    <tr>
                                        <td><b style="text-transform:none !important;">{{ $info['facebook_link'] ?? '' }}</b></td>
                                    </tr>
                                </table>
                            </div>
                            <br />
                            <br />
                            <br />
                            <br />
                            <div class="col-sm-3">
                                <table class="w3-table text-xs">
                                    <tr>
                                        <td><p></p></td>
                                    </tr>
                                    <tr>
                                        <td>Region</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ $info['region'] ?? '' }}</b></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-3">
                                <table class="w3-table text-xs">
                                    <tr>
                                        <td><p></p></td>
                                    </tr>
                                    <tr>
                                        <td>Province</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ $info['province'] ?? '' }}</b></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-sm-3">
                                <table class="w3-table text-xs">
                                    <tr>
                                        <td><p></p></td>
                                    </tr>
                                    <tr>
                                        <td>City/Municipality</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ $info['municipality'] ?? '' }}</b></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-3">
                                <table class="w3-table text-xs">
                                    <tr>
                                        <td><p></p></td>
                                    </tr>
                                    <tr>
                                        <td>Barangay</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ $info['barangay'] ?? '' }}</b></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-sm-12">
                                <table class="w3-table text-xs">
                                    <tr>
                                        <td><p></p></td>
                                    </tr>
                                    <tr>
                                        <td>Purok | Street | House # | Building | Subdivision</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ $info['address_line'] ?? '' }}</b></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="row my-5">
                        <h4 class="border-bottom border-secondary pb-2">
                            <b>
                                Additional Information
                            </b>
                        </h4>
                        <div class="row">
                            <div class="col-sm-4">
                                <table class="w3-table text-xs">
                                    <tr>
                                        <td><p></p></td>
                                    </tr>
                                    <tr>
                                        <td>Learner's Reference Number</td>
                                    </tr>
                                    <tr>
                                        <td><b style="text-transform:none !important;">{{ $info['learner_reference_number'] ?? '' }}</b></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <table class="w3-table text-xs">
                                    <tr>
                                        <td><p></p></td>
                                    </tr>
                                    <tr>
                                        <td>ATM Account Number</td>
                                    </tr>
                                    <tr>
                                        <td><b style="text-transform:none !important;">{{ $info['atm_account_number'] ?? '' }}</b></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <table class="w3-table text-xs">
                                    <tr>
                                        <td><p></p></td>
                                    </tr>
                                    <tr>
                                        <td>Student ID | Number</td>
                                    </tr>
                                    <tr>
                                        <td><b style="text-transform:none !important;">{{ $info['student_id'] ?? '' }}</b></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
