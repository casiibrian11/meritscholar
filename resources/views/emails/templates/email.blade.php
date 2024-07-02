@extends('layouts.email')
@section('content')
    @if (empty($data['content']))
        {!! $data['greetings'] !!}
        <br />
        <br />
        <p style="text-align:justify;">
        {!! $data['default'] !!}

        @if (!in_array($data['template'], ['approved', 'denied']))
        <br />
        <br />
            Please wait for further notifications regarding the status of your application.
        @endif
        </p>
        <br />
        <p>
            Best regards,
            <br />
            <br />
            {{ config('mail')['from']['name'] }}
        </p>
    @else
        {!! $data['content'] !!}
    @endif

@endsection