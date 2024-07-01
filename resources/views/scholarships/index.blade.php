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
                <button class="btn btn-xs btn-success p-1 px-3 d-none" id="new-category" data-toggle="modal" data-target="#modal"
                    data-backdrop="static" data-keyboard="false">
                    <i class="fa fa-plus"></i> ADD NEW CATEGORY
                </button>
                <button class="btn btn-xs btn-success p-1 px-3 view-categories">
                    <i class="fa fa-eye"></i> VIEW SCHOLARSHIP CATEGORIES
                </button>
                <button class="btn btn-xs btn-success add-new p-1 px-3" data-toggle="modal" data-target="#modal"
                    data-backdrop="static" data-keyboard="false">
                    <i class="fa fa-plus"></i> ADD NEW SCHOLARSHIP
                </button>
            </div>
            <div class="card-body">
                <div class="btn-group mb-2 d-none" role="group" aria-label="Active">
                    <a href="/scholarships" type="button" class="btn btn-sm @if (empty($data['visible'])) btn-success @else btn-outline-secondary @endif">All</a>
                    <a href="?visible=yes" type="button" class="d-none btn btn-sm @if (!empty($data['visible']) && $data['visible'] === 'yes') btn-success @else btn-outline-secondary @endif">Visible</a>
                    <a href="?visible=no" type="button" class="d-none btn btn-sm @if (!empty($data['visible']) && $data['visible'] === 'no') btn-success @else btn-outline-secondary @endif">Not visible</a>
                    <a href="?deleted=true" type="button" class="btn btn-sm @if (!empty($data['deleted'])) btn-danger @else btn-outline-secondary @endif">Archived</a>
                </div>
                <div id="categories-container" class="d-none">
                    <h4>
                        <b>
                            Scholarship Categories
                        </b>
                        <button type="button" class="btn btn-xs btn-danger p-1 px-3 hide-categories pull-right" style="float:right;">
                            <i class="fa fa-times"></i> HIDE
                        </button>
                    </h4>
                    @if (count($data['categories']) > 0)
                        <div class="alert alert-info">
                            <strong> <i class="fa fa-info-sign"></i> NOTE: </strong>
                            You can <b>DRAG &amp; DROP</b> scholarship categories to update the sequence. The sorting will reflect to the front page.
                        </div>
                        <div class="table-container">
                            <table class="table table-bordered table-hover table-condensed table-striped text-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Scholarship Category</th>
                                        <th class="text-center" style="width:200px;">View List</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="sortable">
                                    @foreach ($data['categories'] as $category)
                                    <tr data-id="{{ $category['id'] }}">
                                        <td id="sort_number_{{ $category['id'] }}">{{ strtoupper($category['sort_number']) }}</td>
                                        <td>{{ strtoupper($category['category_name']) }}</td>
                                        <td>
                                            <center>
                                                <a href="#" class="btn btn-sm btn-primary p-0 px-2 w-100 view-list"
                                                    data-id="{{ $category['id'] }}">
                                                    <i class="fa fa-eye"></i> View List
                                                </a>
                                            </center>
                                        </td>
                                        <td class="controls">
                                            <center>
                                                <a href="#" class="btn btn-sm btn-warning p-0 px-2 edit edit-category float-left"
                                                    data-id="{{ $category['id'] }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </center>
                                        </td>
                                        <td class="controls">
                                            <center>
                                                <a href="#" class="btn btn-sm btn-danger p-0 px-2 delete category-delete float-left"
                                                    data-id="{{ $category['id'] }}">
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
                    <br />
                    <br />
                </div>
                <div id="scholarships-list"></div>
                <div id="scholarships">
                <h4>
                    <b>
                        List of Scholarships
                    </b>
                </h4>
                @if (count($data['scholarships']) > 0)
                    <div class="table-container">
                    <table class="table table-bordered table-hover table-condensed table-striped text-sm" id="custom">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Privilege</th>
                                <th>Category</th>
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
                                <td class="px-2">
                                    <b>{{ strtoupper($row['description']) }}</b>
                                </td>
                                <td>
                                    @if (!empty($row['privilege']))
                                            &#x20b1;&nbsp;{{ number_format($row['privilege']) }}
                                            @if ($row['is_per_semester'])
                                            &nbsp;per&nbsp;semester
                                            @endif
                                    @endif
                                </td>
                                <td>{{ strtoupper($row['categories']['category_name'] ?? '') }}</td>
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
                                            <a href="#" class="btn btn-sm btn-warning p-0 px-2 edit edit-scholarship float-left"
                                                data-id="{{ $row['id'] }}"
                                                data-scholarship_category_id="{{ $row['scholarship_category_id'] }}"
                                                data-description="{{ $row['description'] }}"
                                                data-privilege="{{ $row['privilege'] }}"
                                                data-is_per_semester="{{ $row['is_per_semester'] }}"
                                                data-requirements="{{ $row['requirements'] }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                    </center>
                                    
                                </td>
                                <td class="controls">
                                    <center>
                                        @if (!$row->trashed())
                                            <a href="#" class="btn btn-sm btn-danger p-0 px-2 delete scholarship-delete float-left"
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
      <div id="scholarships-category">
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
                <div class="form-floating my-3">
                    <select class="form-control" name="scholarship_category_id" id="scholarship_category_id" required>
                        <option value="" disabled selected>SELECT</option>
                        @foreach($data['categories'] as $category)
                            <option value="{{ $category['id'] }}">{{ strtoupper($category['category_name']) }}</option>
                        @endforeach
                    </select>
                    <label for="scholarship_category_id">Scholarship Category <span class="required">*</span></label>
                </div>
                <div class="row mb-2">
                    <input type="hidden" name="is_per_semester" id="is_per_semester" value="false" readonly>
                    <div class="col-sm-8">
                        <div class="form-floating">
                            <input type="number" id="privilege" name="privilege" class="form-control" 
                                placeholder="PRIVILEGE" autocomplete="off">
                                <label for="privilege">PRIVILEGE</label>
                        </div>
                    </div>
                    <div class="col-sm-4 pt-3">
                        <input type="checkbox" id="per_semester"> <label for="per_semester">PER SEMESTER</label>
                    </div>
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
                <button type="button" class="btn close btn-secondary" data-dismiss="modal" aria-label="Close">Close</button>
                <button type="submit" class="btn btn-primary btn-save">Save</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
<script>
function loadForm(id)
{
    $.ajax({
        url:'{{ route("category-form") }}',
        method:'POST',
        data:{
            id:id
        },
        dataType:'json',
        success:function(response){
            $('#scholarships-category').html(response.html);
            $('.close').addClass('category-close');
            $('.modal-footer .close').addClass('category-close');
        }
    });
}

    $(function() {
        $('#requirements').select2({
            width: 'resolve',
            dropdownParent: $('#modal')
        });
        $("#sortable").sortable();
        $("#sortable").on('sortupdate', function(){
            $('#sortable tr').each( function(e) {
                var number = ($(this).index() + 1);
                $.ajax({
                    url:'{{ route("category-sort") }}',
                    method:'POST',
                    data:{
                        id:$(this).data('id'),
                        sort_number: number,
                    },
                    dataType:'json',
                    beforeSend:function(){
                        loader();
                    },
                    success:function(){
                        loaderx();
                    }
                });
                $('#sort_number_'+$(this).data('id')).html(number);
            });
        });

        setTimeout(function(){
            $('#sortable').trigger('sortupdate');
        }, 1000);

        $(document).on('click','.edit-scholarship', function(){
            var requirements = $(this).data('requirements');
            var result = requirements.toString().includes(",");

            if (result) {
                requirements = requirements.split(',');
            }

            $('#requirements').val(requirements);
            $('#requirements').trigger('change');
        });

        $(document).on('click','#new-category', function(){
            $('#scholarships-category').html("");
            var id = null;
            $('#save-route').val('{{ route("category-save") }}');
            loadForm(id);
        });

        $(document).on('click', '.edit-category', function(){
            $('#scholarships-category').html("");
            var id = $(this).data('id');
            $('#save-route').val('{{ route("category-save") }}');
            loadForm(id);
        });

        $('.category-delete').on('click', function(){
            $('#delete-route').val('{{ route("category-delete") }}');
        });

        $('.scholarship-delete').on('click', function(){
            $('#delete-route').val('{{ route("scholarships-delete") }}');
        });

        $(document).on('click','.category-close', function(){
            window.location.reload();
        });

        $('.view-categories').on('click', function(){
            $('#categories-container, #new-category').removeClass('d-none');
            $('.view-categories').addClass('d-none');
        });

        $('.hide-categories').on('click', function(){
            $('#categories-container, #new-category').addClass('d-none');
            $('.view-categories').removeClass('d-none');
            $('#scholarships-list').html('');
            $('#scholarships').removeClass('d-none');
        });

        $(document).on('click','.hide-list', function(){
            $('#scholarships-list').html('');
            $('#scholarships').removeClass('d-none');
        });

        $(document).on('click','.view-list', function(){
            var id = $(this).data('id');

            $.ajax({
                url:'{{ route("scholarships-list") }}',
                method:'POST',
                data:{
                    id:id
                },
                dataType:'json',
                beforeSend:function(){
                    loader();
                },
                success:function(response){
                    loaderx();
                    $('#scholarships-list').html(response.html);
                    $('#scholarships').addClass('d-none');
                }
            });
        });

        $(document).on('click','#per_semester', function(){
            var checked = $('#per_semester').prop('checked');

            if (checked) {
                $('#is_per_semester').val("true");
            } else {
                $('#is_per_semester').val("false");
            }
        });

        $(document).on('click','.edit', function(){
            var is_per_semester = $(this).data('is_per_semester');

            if (is_per_semester) {
                $('#per_semester').prop('checked', true);
            } else {
                $('#per_semester').prop('checked', false);
            }
        });
    });
</script>
@endsection
