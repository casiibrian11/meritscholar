@extends('layouts.admin')

@section('content')
<input type="hidden" class="form-control" id="delete-route" value="{{route('manage-scholarships-delete')}}" readonly>


<h3 class="mt-2 p-0"><i class="fa fa-list-alt"></i> Manage Scholarships ({{ $data['scholarship_offers']->total() }})</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item active">Manage Scholarships</li>
</ol>
<div class="row">
    <div class="col-sm-12 p-0">
        <div class="card p-0 main-body">
            <div class="card-header text-right">
                <a href="/manage-scholarships/manage" class="btn btn-xs btn-success add-new p-1 px-3">
                    <i class="fa fa-plus"></i> ADD NEW
                </a>
            </div>
            <div class="card-body">
                <div class="btn-group mb-2" role="group" aria-label="Active">
                    <a href="/requirements" type="button" class="btn btn-sm @if (empty($data['active'])) btn-success @else btn-outline-secondary @endif">All</a>
                    <a href="?active=yes" type="button" class="btn btn-sm @if (!empty($data['active']) && $data['active'] === 'yes') btn-success @else btn-outline-secondary @endif">Open</a>
                    <a href="?active=no" type="button" class="btn btn-sm @if (!empty($data['active']) && $data['active'] === 'no') btn-success @else btn-outline-secondary @endif">Closed</a>
                    <a href="?deleted=true" type="button" class="btn btn-sm @if (!empty($data['deleted'])) btn-danger @else btn-outline-secondary @endif">Archived</a>
                </div>
                @if (count($data['scholarship_offers']) > 0)
                    <div class="table-container">
                    <table class="table table-bordered table-hover table-condensed table-striped text-sm" id="custom">
                        <thead>
                            <tr>
                                <th>Label</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>File Type</th>
                                <th style="width:50px;">Required?</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['scholarship_offers'] as $key => $row)
                            @if (!empty($row['deleted_at']))
                                <tr class="deleted">
                            @else
                                <tr>
                            @endif
                                <td>{{ $row['label'] }}</td>
                                <td>{{ $row['description'] }}</td>
                                <td>{{ $row['type'] }}</td>
                                <td>{{ $row['file_type'] ?? ""}}</td>
                                {{--
                                <td>
                                    <center>
                                        @if (!$row->trashed())
                                            <a href="/requirements/{{ $row['id'] }}/visibility" class="visibility d-none">
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
                                            <a href="/requirements/{{ $row['id'] }}/restore" class="restore">
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
                                --}}

                                <td>
                                    <center>
                                    @if ($row['required'])
                                        <span class="badge bg-success w-50">Yes</span>
                                    @else
                                        <span class="badge bg-warning w-50">No</span>
                                    @endif
                                    </center>
                                </td>
                                <td class="controls">
                                    @if (!empty($row['sample']))
                                        {{--
                                            <i class="fa fa-image w3-xlarge view-image pointer" data-file="<img src='/storage/requirements/{{ $row['sample'] }}' class='w-100' alt='IMG'>"></i>
                                        --}}
                                        <a href="/storage/requirements/{{ $row['sample'] }}" target="_blank">
                                            <i class="fa fa-image w3-xlarge pointer"></i>
                                        </a>
                                    @endif
                                </td>
                                <td class="controls">
                                    <center>
                                        @if (!$row->trashed())
                                            <a href="/requirements/manage?id={{ $row['id'] }}" class="btn btn-sm btn-warning p-0 px-2 float-left">
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

                    {{ $data['scholarship_offers']->render() }}
                @else
                    @include('layouts.partials._no-record')
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
