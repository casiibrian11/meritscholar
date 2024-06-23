@extends('layouts.admin')

@section('content')
<div class="alert custom-alert alert-block" role="alert" style="z-index:1000000 !important;"></div>
<h3 class="mt-2 p-0"><i class="fa fa-cogs"></i> System Settings</h3>
<ol class="breadcrumb mb-2 text-sm">
    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
    <li class="breadcrumb-item active">System Settings</li>
</ol>
<div class="row">
    <div class="col-sm-12">
        <div class="card main-body">
            <div class="col-sm-12 p-3">
                <div class="form-group my-1">
                    <p class="m-0">
                        <input type="checkbox" id="office_hours_only"> Only allow users to submit applications during office hours
                    </p>
                </div>
                @if ($set['officeHoursOnly'])
                    @if (empty($set['start']['value']) || empty($set['end']['value']))
                        <div class="alert alert-danger p-1">
                            <small>
                                Office hours still not set properly. Users can still send applications anytime of the day.
                            </small>
                        </div>
                    @else
                        <div class="alert alert-success p-1">
                            <small>
                                Users will only be able to send applications during office hours.
                                @if ($set['pastOfficeHours'])
                                    &nbsp;&nbsp;<li class="text-danger">It's beyond office hours. Users won't be able to send applications.</li>
                                @else
                                    <li>Users will still be able to send applications at this time.</li>
                                @endif
                            </small>
                        </div>
                    @endif
                @else
                    <div class="alert alert-success p-1">
                        <small>
                            Users can send applications anytime of the day.
                        </small>
                    </div>
                @endif
                
                @if ($set['officeHoursOnly'])
                    @if (empty($set['start']['value']) || empty($set['end']['value']))
                        <small>
                            <div class="alert alert-danger p-1">
                                You must also set office hours. If these are not set, the users will still be able to send applications anytime.
                            </div>
                        </small>
                    @endif
                @endif
                <div class="form-group my-1 office_hours alert alert-light border border-secondary p-2 d-none">
                    <p class="m-0">
                        &nbsp;
                        &nbsp;
                        Set office hours from <input type="time" class="input" id="office_hours_start"> to <input type="time" class="input" id="office_hours_end">
                    </p>
                </div>

                <div class="form-group mt-3">
                    <p class="m-0">
                        <input type="checkbox" id="allow_weekends"> Allow users to send applications during weekends
                    </p>
                </div>
                @if ($set['weekendsAllowed'])
                    <div class="alert alert-info p-1">
                        <small>Users can send applications during weekends.</small>
                    </div>
                @else
                    <div class="alert alert-info p-1">
                        <small>Users can only send applications during weekdays.</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function alertBlock(type, message) {
    var customAlert = $('.custom-alert');

    if (type == 'success') {
        customAlert.removeClass('alert-danger').addClass('alert-success');
    } else {
        customAlert.addClass('alert-danger').removeClass('alert-success');
    }

    customAlert.show();
    customAlert.html(message);
}
function saveSettings(name, value, reload)
{
    $.ajax({
        url:"{{ route('save-settings') }}",
        method:'POST',
        data:{
            name:name,
            value:value
        },
        dataType:'json',
        beforeSend:function(){
            loader();
        },
        success:function(response){
            loaderx();
            if (response.error) {
                alertBlock('error', response.error);
            } else {
                alertBlock('success', response.success);

                if (reload) {
                    setTimeout(function(){
                        window.location.reload();
                    }, 1000);
                }
            }
        }
    });
}

function toggleOfficeHours(office_hours_only) {
    if (office_hours_only) {
        $('.office_hours').removeClass('d-none');
    } else {
        $('.office_hours').addClass('d-none');
    }
}
    $(function(){
        $('.custom-alert').hide();
        $('#office_hours_only, #allow_weekends').on('change', function(){
            var value = $(this).prop('checked');
            var name = $(this).attr('id');

            if (name == 'office_hours_only') {
                toggleOfficeHours(value);
            }

            saveSettings(name, value, true);
        });

        $('.input').on('blur', function(){
            var value = $(this).val();
            var name = $(this).attr('id');
            saveSettings(name, value, true);
        });

        @if (count($settings) > 0)
            @foreach ($settings as $setting)
                @if ($setting['name'] == 'office_hours_only')
                    @if ($setting['value'])
                        $('#office_hours_only').prop('checked', true);
                        toggleOfficeHours(true);
                    @else
                        $('#office_hours_only').prop('checked', false);
                        toggleOfficeHours(false);
                    @endif
                @endif

                @if ($setting['name'] == 'allow_weekends')
                    @if ($setting['value'])
                        $('#allow_weekends').prop('checked', true);
                    @else
                        $('#allow_weekends').prop('checked', false);
                    @endif
                @endif

                $('#{{ $setting["name"] }}').val('{{ $setting["value"] }}')
            @endforeach
        @endif
    })
</script>
@endsection
