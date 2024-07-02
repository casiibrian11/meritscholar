@extends('layouts.admin')

@section('content')
<h3 class="mt-2 p-0"><i class="fa fa-envelope"></i> Email Templates</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item active">Email Templates</li>
</ol>
<div class="row">
    <div class="col-sm-12">
        <div class="card main-body">
            <div class="col-sm-12 p-3">
                <div class="table-container">
                    <table class="table table-bordered table-hover table-condensed text-sm small">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th style="width:100px;"></th>
                                <th style="width:100px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['statuses'] as $key => $value)
                                <tr>
                                    <td>When an application {{ $value ?? '' }}</td>
                                    <td>
                                        <a href="/emails/templates/view?template={{ $key }}" target="_blank" class="btn btn-primary w-100 p-0 px-2" style="font-size:12px;">
                                            <span class="fa fa-folder-open"></span> View
                                        </a>
                                    </td>
                                    <td>
                                        <a href="/emails/templates/manage?template={{ $key }}" class="btn btn-warning w-100 p-0 px-2" style="font-size:12px;">
                                            <span class="fa fa-edit"></span> Update
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
