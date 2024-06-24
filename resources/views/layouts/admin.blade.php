<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Merit Scholarship Program Online Application | ISU - Main Campus</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


    <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        var _token = $('meta[name=csrf-token').attr('content');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function loader()
        {
            document.getElementById('loader').style.display = 'block';
        }

        function loaderx()
        {
            document.getElementById('loader').style.display = 'none';
        }

        function customAlert(type, message)
        {
            var title = type.toUpperCase()+'!';
            Swal.fire({
                //position: 'top',
                icon: type,
                title: title,
                html: message,
                showConfirmButton: true,
            });
        }

        function remove(id){
            var route = $('#delete-route').val();
            if(route !== '#') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Once submitted, you will not be able to undo this.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed.'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:route,
                            method:'POST',
                            data:{
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                id:id
                            },
                            dataType:'json',
                            success:function(response){
                                if(response.success) {
                                    customAlert('success', response.success);
                                    reload();
                                } else {
                                    customAlert('error', response.error);
                                }
                            }
                        });
                    }
                });
            }
        }

        function reload() {
            setTimeout(function(){
                window.location.reload();
            }, 1000);
        }

        function reset(form){
            $(form)[0].reset();
            $('.modal-title').html('ADD NEW RECORD');
        }

        function TableComparer(index) {
            return function(a, b) {
                var val_a = TableCellValue(a, index);
                var val_b = TableCellValue(b, index);
                var result = ($.isNumeric(val_a) && $.isNumeric(val_b)) ? val_a - val_b : val_a.toString().localeCompare(val_b);

                return result;
            }
        }

        function TableCellValue(row, index) {
            return $(row).children("td").eq(index).text();
        }

        function newApplications() {
            $.ajax({
                url:"{{ route('new-applications') }}",
                method:'POST',
                success:function(response){
                    if (response > 0) {
                        $('#newApplications').html('&nbsp;<span class="badge badge-sm bg-success shadow border border-white" style="color:#FFF !important;">'+response+'</span>');
                    }
                }
            });
        }

        $(function(){
            newApplications();
            const synth = window.speechSynthesis;
            const voices = synth.getVoices();

            $('#simple').DataTable();
            $('#custom').DataTable({
                ordering:false,
                info:false,
                paging:false
            });

            $(document).on('click', '.alert-block', function(){
                $('.alert-block').hide();
            });

            $(document).on('click', '.play', function(){
                document.getElementById('audio').play();
                setTimeout(function(){
                    var utterance = new SpeechSynthesisUtterance("New application submitted.");
                    utterance.volume = 100;
                    utterance.rate = 1;
                    utterance.voice = voices[1];
                    synth.speak(utterance);
                }, 2000);
            });

            $(document).on('click', '.close', function(){
                reset('#form');
            });

            $(document).on('click', '.edit', function(){
                var data = $(this).data();
                $.each(data, function(key, value) {
                    $('#'+key).val(value);
                });
                $('.add-new').trigger('click');
                $('.modal-title').html('UPDATE RECORD');
            });

            $(document).on('click', '.delete', function(){
                var id = $(this).data('id');
                remove(id);
            });

            $(document).on('submit', '#form', function(e){
                e.preventDefault();
                var route = $('#save-route').val();
                if(route !== '#') {
                    $.ajax({
                        url:route,
                        type:'POST',
                        data: new FormData(this),
                        contentType:false,
                        cache:false,
                        processData:false,
                        dataType:'json',
                        beforeSend:function(){
                            loader();
                            $('#modal').addClass('d-none'); 
                            $('.modal-backdrop').addClass('d-none');
                        },
                        success:function(response){
                            loaderx();
                            $('#modal').removeClass('d-none');
                            $('.modal-backdrop').removeClass('d-none'); 
                            if(response.success) {
                                reset('#form');
                                customAlert('success', response.success);
                                //$('.close').trigger('click');
                                reload();
                            } else {
                                console.log(response.error);
                                customAlert('error', response.error);
                            }

                        },
                        error:function(data){
                            loaderx();
                            $('#modal').removeClass('d-none');
                            $('.modal-backdrop').removeClass('d-none');
                            console.log(data);
                            var message = "";
                            var errors = data.responseJSON;
                            $.each( errors.errors, function(key, value) {
                                message += '<li>'+ value +'</li>';
                            });
                            customAlert('error', message);
                        }   
                    });
                }
            });

            $(document).on("click", "table thead tr th:not(.no-sort)", function() {
                var table = $(this).parents("table");
                var rows = $(this).parents("table").find("tbody tr").toArray().sort(TableComparer($(this).index()));
                var dir = ($(this).hasClass("sort-asc")) ? "desc" : "asc";

                if (dir == "desc") {
                    rows = rows.reverse();
                }

                for (var i = 0; i < rows.length; i++) {
                    table.append(rows[i]);
                }

                table.find("thead tr th").removeClass("sort-asc").removeClass("sort-desc");
                $(this).removeClass("sort-asc").removeClass("sort-desc") .addClass("sort-" + dir);
            });

            $(document).on('click', '.visibility', function(e){
                e.preventDefault();
                var href = $(this).attr('href');
                Swal.fire({
                    title: "",
                    text: "Update page visibility?",
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

            $(document).on('click', '.restore', function(e){
                e.preventDefault();
                var href = $(this).attr('href');
                Swal.fire({
                    title: "",
                    text: "Restore record?",
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

            $(document).on('click','.view-image',function(){
                var file = $(this).data('file');
                $('#file .w3-modal-content').html('<center>'+file+'</center>');
                document.getElementById('file').style.display='block';
            });

            $(document).on('click', function(){
                document.getElementById('audio').pause();
            });

            var channel = Echo.private(`new-application`);
            channel.listen('NewApplication', function(data) {
                $('.play').trigger('click');
                newApplications();
            });
        });
    </script>
</head>
<style>
/* Style the container */
.big-icon-container {
    display: flex; /* Ensures the container wraps its contents */
    justify-content: center;

}

/* Style the button */
.big-icon-button {
    background-color: #007A10; /* Set background color */
    border: none;
    color: #ffffff; /* Set text color */
    padding: 10px 20px; /* Add padding */
    font-size: 16px; /* Adjust font size */
    border-radius: 5px; /* Add rounded corners */
    cursor: pointer;
    transition: background-color 0.3s; /* Smooth transition */
}

/* Hover effect */
.big-icon-button:hover {
    background-color: #228B22; /* Darker color on hover */
}

/* Style the icon */
.big-icon-container .fas {
    font-size: 3em; /* Adjust the size of the icon */
}

/* Style the image */
.big-icon-container img {
    width: 250px; /* Adjust the width of the image */
    height: auto; /* Maintain aspect ratio */
}

.footer {
    color: #000 !important;
    text-align: right;
    bottom: 0;
    position: fixed;
    right:0;
    left:0;
    background:#fff !important;
}
.nav-link{
    color:#FFF !important;
}

label{
    font-weight:bold !important;
}

.required {
    font-weight:bold;
    color:red;
}
.alert-block{
    position:absolute !important;
    color:#000 !important;
    top:0;
    left:0;
    right:0;
    cursor:pointer;
    z-index:100000 !important;
}
.capitalize{
    text-transform:capitalize;
}

.uppercase{
    text-transform:uppercase;
}

table{
    font-size:13px;
    margin-bottom:0 !important;
}
table thead tr th, table tbody tr td{
    padding:1px !important;
}

table thead tr th{
    border: 1px solid #aaa !important;
    cursor:pointer;
}

.card{
    padding:0 !important;
}

.text-sm{
    font-size:12px !important;
}

.pointer{
    cursor:pointer;
}

.text-right {
    text-align:right !important;
}

.controls{
    text-align:center;
    width:30px;
}

#custom_filter {
    margin-bottom:10px !important;
    font-size:11px !important;
}

.btn-xs{
    font-size:10px !important;
}

.deleted {
    text-decoration: line-through;
}

.form-floating label{
    font-size:10px !important;
}

.select2-container--open {
    z-index:1000 !important;
    border:1px solid #aaa !important;
}

.text-shadow {
    text-shadow:1px 1px #000;
}


@media screen and (max-width: 600px) {
  .main-body {
    width:100vh;
    max-width:100%;
  }

  .main-body .table-container {
    overflow-x:scroll;
    overflow-y:hidden;
  }
  
  .main-body .table-container table {
    width:1250px;
  }

  .btn-group .btn, .btn-group a{
    width:100% !important;
    font-size:11px !important;
    border-radius:0 !important;
  }
}
</style>
@include('layouts.partials._flash')
<body class="sb-nav-fixed">
<div id="app">
    <div class="w3-modal" id="loader">
        <div class="w3-modal-content w-25 w3-transparent">
            <center>
                <p class="alert alert-info p-0 shadow-lg">
                    <b>Processing...</b>
                </p>
            </center>
        </div>
    </div>

    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="/dashboard">OSAS - SAS </a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <label style="font-size: 15px; color: white;">MERIT SCHOLARSHIP PROGRAM ONLINE APPLICATION</label>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        @if (Auth::user()->user_type == 'admin')
                            <a class="nav-link" href="/dashboard">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="/users">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Users
                            </a>
                            <a class="nav-link" href="/settings">
                                <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                                System Settings
                            </a>

                            <!--- SETTINGS NAV -->
                            
                            <a class="nav-link collapsed" id="setup" href="#" data-bs-toggle="collapse"
                                data-bs-target="#settings" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                                System Setup
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="settings" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="/colleges"><i class="fas fa-building"></i>&nbsp;Colleges</a>
                                </nav>
                            </div>
                            <div class="collapse" id="settings" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="/courses"><i class="fas fa-list"></i>&nbsp;Courses</a>
                                </nav>
                            </div>
                            <div class="collapse" id="settings" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="/sy"><i class="fas fa-calendar"></i>&nbsp;School Years</a>
                                </nav>
                            </div>
                            <div class="collapse" id="settings" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="/requirements"><i class="fas fa-list-alt"></i>&nbsp;Requirements</a>
                                </nav>
                            </div>
                            <div class="collapse" id="settings" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="/scholarships"><i class="fas fa-book-open"></i>&nbsp;Scholarships</a>
                                </nav>
                            </div>

                            <!--- SETTINGS NAV -->

                            <a class="nav-link" href="/manage-scholarships">
                                <div class="sb-nav-link-icon"><i class="fas fa-list-alt"></i></div>
                                Manage&nbsp;Scholarships
                            </a>
                            <br />

                            <a class="nav-link" href="/scholarship/applications" id="trigger">
                                <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div>
                                Manage&nbsp;Applications <span class="ml-3" id="newApplications"></span>
                            </a>
                            <a class="nav-link" href="/scholarship/applications/list">
                                <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                                Applications&nbsp;Masterlist
                            </a>
                        @elseif (Auth::user()->user_type == 'support')
                            <a class="nav-link" href="/dashboard">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="/scholarship/applications">
                                <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div>
                                Manage&nbsp;Applications
                            </a>
                            <a class="nav-link" href="/scholarship/applications/list">
                                <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                                Applications&nbsp;Masterlist
                            </a>
                        @elseif (Auth::user()->user_type == 'director')
                            <a class="nav-link" href="/dashboard">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="/scholarship/applications/list">
                                <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                                Applications&nbsp;Masterlist
                            </a>
                        @endif
                </div>
            </nav>
        </div>
    

    <div id="layoutSidenav_content">
        <main>
        <div class="container-fluid">
            <div class="col-xl-12 p-2">
                @yield('content')
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
            </div>
        </div>
        </main>
    </div>
</div>
<footer class="footer p-3 border">

<audio id="audio">
    <source src="{{ asset('sounds/bell.mp3') }}" type="audio/mpeg">
    <source src="{{ asset('sounds/bell.ogg') }}" type="audio/ogg">
</audio>
<button type="button" class="btn play">Play</button>
    &copy; <?php echo date('Y'); ?> OSAS - Scholarship Application Systems. All rights reserved.
</footer>

<div class="w3-modal pointer" id="file" style="background:rgb(,0,0,0.2);z-index:1000000 !important;" onclick="document.getElementById('file').style.display='none'">
    <div class="w3-modal-content w3-animate-top w3-transparent w-25"></div>
</div>

<!-- Include Font Awesome JS (optional for some features) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>
