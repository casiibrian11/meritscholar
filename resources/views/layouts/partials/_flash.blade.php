@if ($message = session('success'))
<div class="alert alert-success alert-block" role="alert">
    {{ $message }}
</div>
@endif


@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block" role="alert">
     {{ $message }}
</div>
@endif


@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block" role="alert">
	{{ $message }}
</div>
@endif


@if ($message = Session::get('info'))
<div class="alert alert-info alert-block" role="alert">
	{{ $message }}
</div>
@endif

@if ($message = session('message'))
<div class="alert alert-info alert-block" role="alert">
	{{ $message }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-block" role="alert">
	Please check the form below for errors:
    <ul class="mt-2">
        @php
            $previousError = '';
        @endphp
        @foreach ($errors->all() as $error)
            @if (Str::contains($error, 'The flyers.language field is required.'))
                @php
                    $error = 'The flyers language field is required.';
                @endphp
                @if ($error != $previousError)
                    <li>{{ $error }}</li>
                @endif
            @elseif (Str::contains($error, 'image must be a valid image.'))
                @php
                    $error = 'The flyers image must be a valid image.';
                @endphp
                @if ($error != $previousError)
                    <li>{{ $error }}</li>
                @endif
            @else
                <li>{{ ucfirst($error) }}</li>
            @endif
            @php
                $previousError = $error;
            @endphp
        @endforeach
    </ul>
</div>
@endif
