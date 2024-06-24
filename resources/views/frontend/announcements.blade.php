@extends('layouts.app')

@section('content')
<style>
    a{
        color:blue;
    }
</style>
<img src="{{ asset('css/logo5.png') }}" style="width:100%;">
<div class="container mt-3">
    @if (count($announcements) > 0)
        @foreach ($announcements as $data)
        <div class="card my-2">
            <div class="card-body p-0 alert alert-light border border-light m-0">
                <div class="alert alert-light m-0">
                    <b>{{ $data['title'] }}</b>
                </div>
                <div class="col-sm-12 px-4 m-0">
                <small>
                    <span class="badge bg-light text-dark border border-secondary">
                        Posted {{ now()->parse($data['created_at'])->diffForHumans() }}
                    </span>
                    @if (!empty($data['updated_at']))
                        <span class="badge bg-light text-dark border border-secondary">
                            Updated {{ now()->parse($data['updated_at'])->diffForHumans() }}
                        </span>
                    @endif
                </small>
                </div>
                <div class="p-4 border-top border-2 border-secondary mt-2">
                    {!! $data['content'] !!}
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
<br />
<br />
@endsection
