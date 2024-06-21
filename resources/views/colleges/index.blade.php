@extends('layouts.admin')

@section('content')
{{----}}
<input type="hidden" class="form-control" id="save-route" value="{{route('colleges-save')}}" readonly>
<input type="hidden" class="form-control" id="delete-route" value="{{route('colleges-delete')}}" readonly>


<h3 class="mt-2 p-0"><i class="fa fa-building"></i> Colleges ({{ $colleges->total() }})</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item active">Colleges</li>
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
                    <a href="/colleges" type="button" class="btn btn-sm @if (empty($data['visible'])) btn-success @else btn-outline-secondary @endif">All</a>
                    <a href="?visible=yes" type="button" class="btn btn-sm @if (!empty($data['visible']) && $data['visible'] === 'yes') btn-success @else btn-outline-secondary @endif">Visible</a>
                    <a href="?visible=no" type="button" class="btn btn-sm @if (!empty($data['visible']) && $data['visible'] === 'no') btn-success @else btn-outline-secondary @endif">Not visible</a>
                    <a href="?deleted=true" type="button" class="btn btn-sm @if (!empty($data['deleted'])) btn-danger @else btn-outline-secondary @endif">Archived</a>
                </div>
                @if (count($colleges) > 0)
                    <div class="table-container">
                    <table class="table table-bordered table-hover table-condensed table-striped" id="custom">
                        <thead>
                            <tr>
                                <th>College Code</th>
                                <th>College Name</th>
                                <th>College Dean</th>
                                <th><center>Page Visibility</center></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($colleges as $key => $row)
                            @if (!empty($row['deleted_at']))
                                <tr class="deleted">
                            @else
                                <tr>
                            @endif
                                <td>{{strtoupper($row['college_code'])}}</td>
                                <td>{{strtoupper($row['college_name'])}}</td>
                                <td>{{strtoupper($row['college_dean'])}}</td>
                                <td>
                                    <center>
                                    @if (!$row->trashed())
                                    <a href="/colleges/{{ $row['id'] }}/visibility" class="visibility">
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
                                        <a href="/colleges/{{ $row['id'] }}/restore" class="restore">
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
                                    @if (!$row->trashed())
                                    <center>
                                    <a href="#" class="btn btn-sm btn-warning p-0 px-2 edit float-left"
                                        data-id="{{ $row['id'] }}"
                                        data-college_code="{{ $row['college_code'] }}"
                                        data-college_name="{{ $row['college_name'] }}"
                                        data-college_dean="{{ $row['college_dean'] }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    </center>
                                    @endif
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

                    {{ $colleges->render() }}
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
            @include('layouts.required')
            @csrf
            <input type="hidden" name="id" id="id" readonly>
            <div class="form-floating mb-2">
                <input type="text" id="college_code" name="college_code" class="form-control uppercase" 
                    placeholder="College Code" autocomplete="off">
                    <label for="name">College Code <span class="required">*</span></label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" id="college_name" name="college_name" class="form-control uppercase" 
                    placeholder="College Name" autocomplete="off">
                    <label for="name">College Name <span class="required">*</span></label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" id="college_dean" name="college_dean" class="form-control uppercase" 
                    placeholder="College Dean" autocomplete="off">
                    <label for="email">College Dean <i>(optional)</i></label>
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
@endsection
