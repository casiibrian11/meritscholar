@extends('layouts.admin')

@section('content')
<input type="hidden" class="form-control" id="save-route" value="{{route('sy-save')}}" readonly>
<input type="hidden" class="form-control" id="delete-route" value="{{route('sy-delete')}}" readonly>


<h3 class="mt-2 p-0"><i class="fa fa-calendar"></i> School Years ({{ $data['school_years']->total() }})</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item active">School Years</li>
</ol>
<div class="row">
    <div class="col-sm-12 p-0">
        <div class="card p-0 main-body">
            <div class="card-header text-right">
                <button class="btn btn-xs btn-success add-new p-1 px-3" data-toggle="modal" data-target="#modal"
                    data-backdrop="static" data-keyboard="false">
                    <i class="fa fa-plus"></i> ADD NEW
                </button>
            </div>
            <div class="card-body">
                <div class="btn-group mb-2" role="group" aria-label="Active">
                    <a href="/sy" type="button" class="btn btn-sm @if (empty($data['visible'])) btn-success @else btn-outline-secondary @endif">All</a>
                    <a href="?visible=yes" type="button" class="btn btn-sm @if (!empty($data['visible']) && $data['visible'] === 'yes') btn-success @else btn-outline-secondary @endif">Visible</a>
                    <a href="?visible=no" type="button" class="btn btn-sm @if (!empty($data['visible']) && $data['visible'] === 'no') btn-success @else btn-outline-secondary @endif">Not visible</a>
                    <a href="?deleted=true" type="button" class="btn btn-sm @if (!empty($data['deleted'])) btn-danger @else btn-outline-secondary @endif">Archived</a>
                </div>
                @if (count($data['school_years']) > 0)
                    <div class="table-container">
                    <table class="table table-bordered table-hover table-condensed table-striped text-sm" id="custom">
                        <thead>
                            <tr>
                                <th>From</th>
                                <th>To</th>
                                <th>Semester</th>
                                <th><center>Page Visibility</center></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['school_years'] as $key => $row)
                            @if (!empty($row['deleted_at']))
                                <tr class="deleted">
                            @else
                                <tr>
                            @endif
                                <td>{{ strtoupper($row['start_year']) }}</td>
                                <td>{{ strtoupper($row['end_year']) }}</td>
                                <td>
                                    {{ $row['semester'] }} @if ($row['semester'] <> 'Summer') Semester @endif
                                </td>
                                <td>
                                    <center>
                                        @if (!$row->trashed())
                                            <a href="/sy/{{ $row['id'] }}/visibility" class="visibility">
                                                @if ($row['visible'])
                                                    <span class="badge bg-success">
                                                        <i class="fa fa-check"></i> visible
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fa fa-times"></i> not visible
                                                    </span>
                                                @endif
                                            </a>
                                        @else
                                            <a href="/sy/{{ $row['id'] }}/restore" class="restore">
                                                <span class="badge bg-danger">
                                                    <i class="fa fa-times"></i> deleted
                                                </span>
                                                <span class="badge bg-info">
                                                    <i class="fa fa-undo"></i> restore
                                                </span>
                                            </a>
                                        @endif
                                    </center>
                                </td>
                                <td class="controls">
                                    <center>
                                        @if (!$row->trashed())
                                            <a href="#" class="btn btn-sm btn-warning p-0 px-2 edit float-left"
                                                data-id="{{ $row['id'] }}"
                                                data-start_year="{{ $row['start_year'] }}"
                                                data-end_year="{{ $row['end_year'] }}"
                                                data-semester="{{ $row['semester'] }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                    </center>
                                    
                                </td>
                                <td class="controls">
                                    <center>
                                        @if (!$row->trashed())
                                            <a href="#" class="btn btn-sm btn-danger p-0 px-2 delete float-left"
                                                data-id="{{$row->id}}">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endif
                                    </center>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <br />

                    {{ $data['school_years']->render() }}
                @else
                    @include('layouts.partials._no-record')
                @endif
            </div>
        </div>
    </div>
</div>

<!--- MODAL -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="z-index:1 !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
            ADD NEW RECORD
        </h5>
        <button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
          <span class="h4" aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="POST" id="form">
        <div class="modal-body">
            <p class="alert alert-info small">
                <strong><i class="fa fa-info-circle"></i> NOTE:</strong> Fields marked with <span class="required">*</span> are required.
            </p>
            @csrf
            <input type="hidden" name="id" id="id" readonly>
            <div class="form-floating mb-2">
                <select class="form-control" name="start_year" id="start_year">
                    <option value="" disabled selected>SELECT</option>
                    @for ($i = date('Y') - 2; $i <= date('Y') + 10; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                <label for="start_year">FROM <span class="required">*</span></label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" id="end_year" name="end_year" class="form-control uppercase" 
                    placeholder="TO" autocomplete="off" readonly>
                    <label for="end_year">TO <span class="required">*</span></label>
            </div>
            <div class="form-floating mb-2">
                <select class="form-control" name="semester" id="semester">
                    <option value="" disabled selected>SELECT</option>
                    <option value="1st">1st</option>
                    <option value="2nd">2nd</option>
                    <option value="3rd">3rd</option>
                    <option value="Summer">Summer</option>
                </select>
                <label for="semester">Semester <span class="required">*</span></label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn close btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-save">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    $(function(){
        $('#start_year').on('change', function(){
            $('#end_year').val("");
            var start = $(this).val();
            var end = (parseInt(start) + 1);
            $('#end_year').val(end);
        });
    });
</script>
@endsection
