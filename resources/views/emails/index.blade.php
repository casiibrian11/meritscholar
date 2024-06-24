@extends('layouts.admin')

@section('content')
<h3 class="mt-2 p-0"><i class="fa fa-envelope"></i> Email Activities</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item active">Email Activities</li>
</ol>
<div class="row">
    <div class="col-sm-12">
        <div class="card main-body">
            <div class="col-sm-12 p-3">
                <form action="" method="GET">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <a href="/emails">
                                <button class="btn btn-outline-secondary" type="button"><i class="fa fa-eraser"></i> Clear</button>
                            </a>
                        </div>
                        <input type="text" class="form-control border-dark" placeholder="Search (email)..." name="email" 
                            value="@if (!empty($result['email'])){{ $result['email'] }}@endif">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </form>
                @if (!empty($result['events']) && count($result['events']) > 0)
                    <div class="table-container">
                        <table class="table table-bordered table-hover table-condensed table-striped text-sm small">
                            <thead>
                                <tr>
                                    <th>Recipient</th>
                                    <th>Date</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Sender</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result['events'] as $email)
                                    @if (in_array($email['email'], $result['emails']))
                                        @php
                                            continue;
                                        @endphp
                                    @endif
                                    @php
                                        if ($email['event'] == 'opened') {
                                            $bg = 'bg-success text-white';
                                        } elseif ($email['event'] == 'delivered') {
                                            $bg = 'bg-primary';
                                        } elseif ($email['event'] == 'requests') {
                                            $bg = 'bg-warning';
                                        } else {
                                            $bg = 'bg-danger';
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $email['email'] ?? '' }}</td>
                                        <td>
                                            @if (strtotime($email['date']) !== false)
                                                <span class="badge bg-light text-dark">{{ now()->parse($email['date'])->format('M j, Y h:ia') }}</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-light text-dark">{{ $email['subject'] ?? ''}}</span></td>
                                        <td><span class="badge {{ $bg }} text-dark text-center">{{ $email['event'] ?? ''}}</span></td>
                                        <td><span class="badge bg-light text-dark">{{ $email['from'] ?? ''}}</span></td>
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
