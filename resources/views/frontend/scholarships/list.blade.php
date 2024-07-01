@extends('layouts.app')

@section('content')
<style>
    .shadow{
        box-shadow:0 0 10px 5px #aaa;
    }
    a{
        text-decoration:none !important;
    }
    a:hover{
        text-decoration-color:transparent !important;
    }
</style>
<img src="{{ asset('css/logo5.png') }}" style="width:100%;">
<div class="container mt-3">
    @if (count($data) > 0)
        <div class="row justify-content-center">
            <div class="col-sm-11">
                <div class="row nav nav-center mb-3 bold">
                    <a class="btn col-6 col-lg-4 ml-1 tables" data-toggle="tab" href="#tables">SCHOLARSHIPS BROCHURE</a>
                    <a class="btn col-6 col-lg-4 ml-1 lists" data-toggle="tab" href="#list">DETAILED LIST</a>
                    @if (count($data['applications']) > 0)
                        <a href="/scholarships/{{ $data['sy_id'] }}/application"
                            class="btn col-6 col-lg-4 ml-1 btn-success">
                            <i class="fa fa-list"></i> MY APPLICATIONS
                        </a>
                    @endif
                </div>
                <!--- TABLE -->
                <div id="tables" class="tab-pane fade in">
                    @if (count($data['categories']))
                        <table class="table table-sm table-bordered table-condensed small">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th class="w-50">TYPES OF SCHOLARSHIP</th>
                                    <th class="w-50">PRIVELEGES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['categories'] as $category)
                                    <tr class="categories bg-light" 
                                        data-id="{{ $category['id'] }}">
                                        <td colspan="2" class="p-3">
                                            <b>{{ strtoupper($category['category_name']) }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="p-0">
                                            <div class="row">
                                                <div class="col-sm-12 px-4" id="category_{{ $category['id'] }}"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                    <div class="row justify-content-center">
                        <div class="col-sm-8 border rounded-4 shadow p-4 alert alert-danger">
                            <center>
                                I'm sorry, no record was found.
                            </center>
                        </div>
                    </div>
                    @endif
                </div>
                <!--- TABLE -->
                <!--- LIST -->
                <div id="list" class="tab-pane fade in">
                
                    @php
                        $count = 0;
                    @endphp
                    @foreach ($data['offers'] as $offer)
                        @if (now()->gt($offer['date_from']) && now()->lt($offer['date_to']))
                            @php
                                $count += 1;
                            @endphp
                        @endif
                    @endforeach
                    

                    @if ($count > 0)
                        <div class="col-sm-12 p-5 border rounded-3 shadow">
                            <div class="alert alert-success p-1 px-3" style="text-align:justify;">
                                <i class="fa fa-info-circle"></i> Check below for complete details and list of requirements.
                            </div>
                            <a href="/scholarships/{{ $data['sy_id'] }}/application" class="btn btn-success mt-2 px-5">
                                <i class="fa fa-check"></i> Apply now!
                            </a>
                        </div>
                    @endif

                    <ol class="mt-4">
                        @foreach ($data['offers'] as $offer)
                            <li>
                                <b class="h4"> {{ strtoupper($offer['scholarships']['description']) }} </b>

                                <p>
                                    <div class="badge @if (now()->parse($offer['date_to'])->lt(now())) bg-danger @else bg-success @endif px-3 p-1" style="font-weight:normal !important;font-size:15px;">
                                        @if (now()->lt($offer['date_from']))
                                            Application will start on {{ now()->parse($offer['date_from'])->format('F j, Y') }}.
                                        @endif
                                        @if (now()->parse($offer['date_to'])->gt(now()))
                                            Deadline of application is until <b>{{ now()->parse($offer['date_to'])->format('F j, Y') }}</b> <small>({{ str_replace('from now', 'left', now()->parse($offer['date_to'])->diffForHumans()) }})</small>
                                        @else
                                            Deadline of application has ended last {{ now()->parse($offer['date_to'])->format('F j, Y') }}
                                        @endif
                                    </div>
                                </p>
                                @if (!empty($offer['scholarships']['requirements']))
                                    <h5><b><i class="fa fa-list"></i> List of requirements</b></h5>
                                    @php
                                        $listOfRequirements = explode(',', $offer['scholarships']['requirements']);
                                    @endphp

                                    <ul>
                                        @foreach ($listOfRequirements as $key => $value)
                                            @if (!empty($data['requirements'][$value]))
                                                <li>
                                                    {{ $data['requirements'][$value]['label'] }}
                                                    @if ($data['requirements'][$value]['required'])
                                                        <span class="badge badge-sm bg-success tiny m-auto">required</span>
                                                    @endif
                                                    <p class="alert alert-info" style="text-align:justify;">
                                                        <i class="fa fa-info-circle"></i> {!! $data['requirements'][$value]['description'] !!}
                                                        <br />
                                                        @if ($data['requirements'][$value]['sample'])
                                                            <a href="/storage/requirements/{{ $data['requirements'][$value]['sample'] }}" target="_blank">
                                                                <span class="badge bg-secondary tiny">
                                                                    <i class="fa fa-folder-open"></i> Click to view sample
                                                                </span>
                                                            </a>
                                                        @endif
                                                    </p>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        <br />
                        <br />
                        @endforeach
                    </ol>
                </div>
                <!--- LIST -->
            </div>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-sm-8 border rounded-4 shadow p-4 alert alert-danger">
                <center>
                    I'm sorry, there's no scholarship offering as of the moment. <br /> Please wait for further announcements.
                </center>
            </div>
        </div>
    @endif
</div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(function(){
        $('.tables').trigger('click');
        var categories = $('.categories');

        if (categories.length > 0) {
            $.each(categories, function(){
                var id = $(this).data('id');

                $.ajax({
                    url:'{{ route("scholarships-table") }}',
                    method:'POST',
                    data:{
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id:id
                    },
                    dataType:'json',
                    success:function(response){
                        $('#category_'+id).html(response.html);
                    }
                });
            });
        }

        $(document).on('click','.tables', function(){
            $('#list').addClass('d-none');
            $('#tables').removeClass('d-none');
        });

        $(document).on('click','.lists', function(){
            $('#tables').addClass('d-none');
            $('#list').removeClass('d-none');
        });
    });
</script>
