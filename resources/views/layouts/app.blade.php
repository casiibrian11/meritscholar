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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        $(function(){
            $(document).on('click', '.alert-block', function(){
                $('.alert-block').hide();
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
    color: black;
    text-align: right;
    bottom: 0;
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
    position:absolute;
    top:0;
    left:0;
    right:0;
    cursor:pointer;
}

.w3-table tr td{
    padding:0;
    margin:0;
    font-size:14px;
}
.tiny {
    font-size:8px;
}

.small {
    font-size:12px;
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

  .btn-group, .btn-group .btn{
    width:100% !important;
    font-size:11px !important;
  }
  img{
    width:100% !important;
  }
}
</style>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-fixed-top shadow-sm">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link"  href="https://isu.edu.ph/" target="_blank">ISU</a>
                        </li>
                        @guest
                            <li class="nav-item">
                                <a class="nav-link"  href="/">Home</a>
                            </li>
                        @else
                            @if (Auth::user()->user_type == 'student')
                                <li class="nav-item">
                                    <a class="nav-link"  href="/">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"  href="/scholarships/list">List of scholarships</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link"  href="/dashboard">Dashboard</a>
                                </li>
                            @endif
                        @endif
                        @if (\App\Models\Announcement::where('visible', true)->count() > 0)
                        <li class="nav-item">
                            <a class="nav-link"  href="/page/announcements">
                                <i class="fa fa-bullhorn"></i> Announcements
                            </a>
                        </li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fa fa-user"></i> {{ ucwords(Auth::user()->first_name) }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/profile"><i class="fa fa-user"></i> Profile</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="w3-modal" id="loader">
            <div class="w3-modal-content w-25 w3-transparent">
                <center>
                    <p class="alert alert-info p-0 shadow-lg">
                        <b>Processing...</b>
                    </p>
                </center>
            </div>
        </div>
        <main>
            @include('layouts.partials._flash')
            @yield('content')
        </main>
    </div>
<!-- Include Font Awesome JS (optional for some features) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</div>

<!-- Include Font Awesome JS (optional for some features) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>
