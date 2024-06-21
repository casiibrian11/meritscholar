@extends('layouts.admin')

@section('content')
<h1 class="mt-2 p-0"><i class="fa fa-users"></i> Users ({{ $users->total() }})</h1>
<div class="row">
    <div class="col-sm-12 p-0">
        <div class="card p-0 main-body">
            <div class="card-body">
                <div class="btn-toolbar mb-3" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group" role="group" aria-label="User Types">
                        <a href="/users" type="button" class="btn btn-sm @if (empty($data['user_type'])) btn-success @else btn-outline-secondary @endif">All</a>
                        <a href="?user_type=admin" type="button" class="btn btn-sm @if ($data['user_type'] == 'admin') btn-success @else btn-outline-secondary @endif">Admin</a>
                        <a href="?user_type=support" type="button" class="btn btn-sm @if ($data['user_type'] == 'support') btn-success @else btn-outline-secondary @endif">Support</a>
                        <a href="?user_type=student" type="button" class="btn btn-sm @if ($data['user_type'] == 'student') btn-success @else btn-outline-secondary @endif">Students</a>
                        <a href="?user_type=director" type="button" class="btn btn-sm @if ($data['user_type'] == 'director') btn-success @else btn-outline-secondary @endif">Director</a>
                    </div>
                    &nbsp;
                    &nbsp;
                    <div class="btn-group" role="group" aria-label="Status">
                        <a href="?verified=yes" type="button" class="btn btn-sm @if (!empty($data['verified']) && $data['verified'] === 'yes') btn-success @else btn-outline-secondary @endif">Verified</a>
                        <a href="?verified=no" type="button" class="btn btn-sm @if (!empty($data['verified']) && $data['verified'] === 'no') btn-success @else btn-outline-secondary @endif">Not Verified</a>
                    </div>
                    &nbsp;
                    &nbsp;
                    <div class="btn-group" role="group" aria-label="Active">
                        <a href="?active=yes" type="button" class="btn btn-sm @if (!empty($data['active']) && $data['active'] === 'yes') btn-success @else btn-outline-secondary @endif">Active</a>
                        <a href="?active=no" type="button" class="btn btn-sm @if (!empty($data['active']) && $data['active'] === 'no') btn-success @else btn-outline-secondary @endif">Disabled</a>
                    </div>
                </div>

                <form action="/users" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search..." name="keyword" 
                            value="@if (!empty($data['keyword'])){{ $data['keyword'] }}@endif">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </form>

                @if (!empty($data['keyword']))
                    <div class="alert alert-success p-1 text-sm">
                        Showing results for keyword: <b>{{ $data['keyword'] }}</b>
                    </div>
                @endif

                @if (count($users) > 0)
                    <div class="table-container">
                    <table class="table table-bordered table-hover table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th><center>Status</center></th>
                                <th><center>Active</center></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="capitalize">{{ $user['last_name'] }}, {{ $user['first_name'] }} {{ $user['middle_name'] }} {{ $user['name_extension'] }}</td>
                                    <td>{{ $user['email'] }}</td>
                                    <td>
                                        <span class="badge @if ($user['user_type'] == 'admin') bg-primary @elseif ($user['user_type'] == 'director') bg-info @else bg-warning @endif">{{ $user['user_type'] }}</span>
                                        @if (Auth::user()->id <> $user['id'])
                                            <select name="user_type" class="user_type" id="user_type{{ $user['id'] }}" data-id="{{ $user['id'] }}" data-user_type="{{ $user['user_type'] }}">
                                                <option value="admin" @if($user['user_type'] == 'admin') selected @endif>admin</option>
                                                <option value="support" @if($user['user_type'] == 'support') selected @endif>support</option>
                                                <option value="student" @if($user['user_type'] == 'student') selected @endif>student</option>
                                                <option value="director" @if($user['user_type'] == 'director') selected @endif>director</option>
                                            </select>
                                        @endif
                                    </td>
                                    <td>
                                        <center>
                                        @if (Auth::user()->id <> $user['id'])
                                        <a href="/users/{{$user['id']}}/verification" data-status="{{ $user['is_active'] }}" class="verification">
                                        @else
                                        <a href="#" class="invalid">
                                        @endif
                                            @if ($user['is_verified'])
                                                <span class="badge bg-success pointer"><i class="fa fa-check-circle"></i> verified</span>
                                            @else
                                                <span class="badge bg-danger pointer"><i class="fa fa-times"></i> not verified</span>
                                            @endif
                                            </a>
                                        </center>
                                    </td>
                                    <td>
                                        <center>
                                        @if (Auth::user()->id <> $user['id'])
                                        <a href="/users/{{$user['id']}}/update" data-status="{{ $user['is_active'] }}" class="update">
                                        @else
                                        <a href="#" class="invalid">
                                        @endif
                                            @if ($user['is_active'])
                                                <span class="badge bg-success pointer"><i class="fa fa-check-circle"></i></span>
                                            @else
                                                <span class="badge bg-danger pointer"><i class="fa fa-times"></i></span>
                                            @endif
                                        </a>
                                        </center>
                                    </td>
                                    <td class="p-0">
                                    <center>
                                        @if (Auth::user()->id <> $user['id'])
                                        <a href="/users/{{$user['id']}}/delete?{{ time() }}" class="btn btn-danger btn-sm p-0 px-2 remove">
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
                    {{ $users->links() }}
                @else
                    @include('layouts.partials._no-record')
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('.remove').on('click', function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: "Are you sure?",
                text: "Once deleted, this cannot be undone. Proceed anyway?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        $('.update').on('click', function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            var active = $(this).data('status');
            var message = "";

            message = "Activate";
            if (active) {
                message = "Deactivate";
            }

            Swal.fire({
                title: message+" user's account?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        $('.verification').on('click', function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: "Update user's account verification status?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        $('.invalid').on('click', function(e){
            e.preventDefault();
            Swal.fire({
                title: "Oops!",
                text: "You are not allowed update your own status.",
                icon: "error"
            });
        });

        $('.user_type').on('change', function(){
            var user_type = $(this).val();
            var id = $(this).data('id');
            var old_type = $(this).data('user_type');
            var href = '/users/'+id+'/user_type?user_type='+user_type;

            Swal.fire({
                title: "",
                text: "Update user's access control to "+user_type+"?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
                if (result.isDismissed) {
                    $('#user_type'+id).val(old_type);
                }
            });
        });
    })
</script>
@endsection