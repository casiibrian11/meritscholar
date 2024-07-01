@if (count($data['scholarships']) > 0)
<h4>
    <b>
        Scholarship list for <b>{{ strtoupper($data['category']['category_name'] ?? '') }}</b>
    </b>
    <button type="button" class="btn btn-xs btn-danger p-1 px-3 hide-list pull-right" style="float:right;">
        <i class="fa fa-times"></i> HIDE
    </button>
</h4>
<div class="table-container mt-3">
    <table class="table table-bordered table-hover table-condensed table-striped text-sm">
        <thead>
            <tr>
                <th></th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody id="sortable2">
            @foreach ($data['scholarships'] as $key => $row)
                <tr data-id="{{ $row['id'] }}">
                    <td id="sort_{{ $row['id'] }}">{{ $row['sort_number'] ?? '' }}</td>
                    <td>{{ strtoupper($row['description']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
@else
    @include('layouts.partials._no-record')
@endif

<script>
    setTimeout(function(){
            $('#sortable2').trigger('sortupdate');
    }, 1000);
    $("#sortable2").sortable();
    $("#sortable2").on('sortupdate', function(){
        $('#sortable2 tr').each( function(e) {
            var number = ($(this).index() + 1);
            $.ajax({
                url:'{{ route("scholarships-sort") }}',
                method:'POST',
                data:{
                    id:$(this).data('id'),
                    sort_number: number,
                },
                dataType:'json',
                beforeSend:function(){
                    loader();
                },
                success:function(){
                    loaderx();
                }
            });
            $('#sort_'+$(this).data('id')).html(number);
        });
    });
</script>