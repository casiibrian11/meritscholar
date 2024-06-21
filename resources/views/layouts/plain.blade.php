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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">

    <script>
        var _token = $('meta[name=csrf-token').attr('content');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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

        $(function(){
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

  .btn-group, .btn-group .btn{
    width:100% !important;
    font-size:11px !important;
  }
}
</style>
<body>
<div id="app">
    <main>
        <div class="container-fluid">
            <div class="col-xl-12 p-2">
                @yield('content')
            </div>
        </div>
    </main>
</div>

<!-- Include Font Awesome JS (optional for some features) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>
