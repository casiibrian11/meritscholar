@extends('layouts.admin')

@section('content')
<input type="hidden" id="delete-route" value="{{ route('announcements-delete') }}" readonly>
<h3 class="mt-2 p-0"><i class="fa fa-bullhorn"></i> Announcements</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item active">Announcements</li>
</ol>
<div class="row">
    <div class="col-sm-12">
        <div class="card main-body">
            <div class="card-header text-right">
                <a href="/announcements/manage" class="btn btn-xs btn-success add-new p-1 px-3">
                    <i class="fa fa-plus"></i> ADD NEW
                </a>
            </div>
            <div class="col-sm-12 p-3">
                @if (count($data['announcements']) > 0)
                    <div class="table-container">
                        <table class="table table-bordered table-hover table-condensed table-striped text-sm small">
                            <thead>
                                <tr>
                                    <th>Content</th>
                                    <th>Page Visibility</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['announcements'] as $row)
                                    <tr>
                                        <td class="w-75">{!! $row['content'] ?? '' !!}</td>
                                        <td>
                                            <a href="/announcements/{{ $row['id'] }}/visibility" class="visibility">
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
                                        </td>
                                        <td class="controls">
                                            <center>
                                                <a href="/announcements/manage?id={{ $row['id'] }}" class="btn btn-sm btn-warning p-0 px-2 float-left">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </center>
                                            
                                        </td>
                                        <td class="controls">
                                            <center>
                                                <a href="#" class="btn btn-sm btn-danger p-0 px-2 delete float-left"
                                                    data-id="{{ $row['id'] }}">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </center>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    @include('layouts.partials._no-record')
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
