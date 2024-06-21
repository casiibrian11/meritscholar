@extends('layouts.admin')

@section('content')
<input type="hidden" class="form-control" id="save-route" value="{{route('scholarships-save')}}" readonly>
<input type="hidden" class="form-control" id="delete-route" value="{{route('scholarships-delete')}}" readonly>


<h3 class="mt-2 p-0"><i class="fa fa-book-open"></i> Scholarships ({{ $data['scholarships']->total() }})</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item active">Scholarships</li>
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
                    <a href="/scholarships" type="button" class="btn btn-sm @if (empty($data['visible'])) btn-success @else btn-outline-secondary @endif">All</a>
                    <a href="?visible=yes" type="button" class="d-none btn btn-sm @if (!empty($data['visible']) && $data['visible'] === 'yes') btn-success @else btn-outline-secondary @endif">Visible</a>
                    <a href="?visible=no" type="button" class="d-none btn btn-sm @if (!empty($data['visible']) && $data['visible'] === 'no') btn-success @else btn-outline-secondary @endif">Not visible</a>
                    <a href="?deleted=true" type="button" class="btn btn-sm @if (!empty($data['deleted'])) btn-danger @else btn-outline-secondary @endif">Archived</a>
                </div>
                @if (count($data['scholarships']) > 0)
                    <div class="table-container">
                    <table class="table table-bordered table-hover table-condensed table-striped text-sm" id="custom">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Requirements</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['scholarships'] as $key => $row)
                            @if (!empty($row['deleted_at']))
                                <tr class="deleted">
                            @else
                                <tr>
                            @endif
                                <td>{{ strtoupper($row['description']) }}</td>
                                <td>
                                    @php
                                        $requirementArray = explode(',', $row['requirements']);
                                    @endphp

                                    <ul class="m-0 p-0 px-4">
                                    @foreach ($requirementArray as $key => $value)
                                        @if (!empty($data['array'][$value]))
                                            <li>{{ $data['array'][$value]['label'] }}</li>   
                                        @endif
                                    @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <center>
                                        @if (!$row->trashed())
                                            <a href="/scholarships/{{ $row['id'] }}/visibility" class="visibility d-none">
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
                                            <a href="/scholarships/{{ $row['id'] }}/restore" class="restore">
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
                                                data-description="{{ $row['description'] }}"
                                                data-requirements="{{ $row['requirements'] }}">
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

                    {{ $data['scholarships']->render() }}
                @else
                    @include('layouts.partials._no-record')
                @endif
            </div>
        </div>
    </div>
</div>

<!--- MODAL -->
<div class="modal fade modal-lg" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <input type="text" id="description" name="description" class="form-control uppercase" 
                    placeholder="DESCRIPTION" autocomplete="off">
                    <label for="description">DESCRIPTION <span class="required">*</span></label>
            </div>
            <div class="form-group">
                <label class="text-sm">Requirements for this scholarship <span class="required">*</span></label>
                <select class="form-control" multiple="multiple" name="requirements[]" id="requirements" style="width:100% !important;height:100px !important;" required>
                    @foreach($data['requirements'] as $requirement)
                        <option value="{{ $requirement['id'] }}">{{ strtoupper($requirement['label']) }}</option>
                    @endforeach
                </select>
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
    $(function() {
        $('#requirements').select2({
            width: 'resolve',
            dropdownParent: $('#modal')
        });

        $('.edit').on('click', function(){
            var requirements = $(this).data('requirements');
            requirements = requirements.split(',');
            $('#requirements').val(requirements).trigger("change");
        });
    });
</script>
@endsection
