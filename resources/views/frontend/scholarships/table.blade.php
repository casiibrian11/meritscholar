@php
    $alphabet = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
@endphp
<div class="row">
@foreach($data['scholarships'] as $key => $row)
    <div class="w-50 border p-2">&nbsp;&nbsp;&nbsp;&nbsp;{{ $alphabet[$key] }}.&nbsp;{{ strtoupper($row['description']) }}</div>
    <div class="w-50 border p-2">
        {{ $alphabet[$key] }}.&nbsp;&#x20b1;{{ number_format($row['privilege']) }}@if ($row['is_per_semester']) per&nbsp;semester @endif
    </div>
@endforeach
</div>
