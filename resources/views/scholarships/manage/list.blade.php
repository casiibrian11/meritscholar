<div class="table-container">
<table class="table table-bordered table-sm table-condensed table-hover mt-5">
    <thead>
        <tr>
            <th></th>
            <th class="text-center">Scholarship</th>
            <th class="text-center">Start Date</th>
            <th class="text-center">End Date</th>
            <th class="text-center">Active</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['scholarships'] as $scholarship)
            <tr>
                <td class="text-center">
                    <input type="text" class="form-control d-none" name="id" id="id_{{ $scholarship['id'] }}" readonly>
                        <span id="status_{{ $scholarship['id'] }}">
                            <span class="badge bg-success d-none">
                                <i class="fa fa-check"></i>
                            </span>
                        </span>
                </td>
                <td>{{ strtoupper($scholarship['description']) }}</td>
                <td>
                    <input type="date" class="form-control text-sm" name="date_from" id="date_from_{{ $scholarship['id'] }}">
                </td>
                <td>
                    <input type="date" class="form-control text-sm" name="date_to" id="date_to_{{ $scholarship['id'] }}">
                </td>
                <td class="text-center pt-2">
                    <input type="checkbox" name="active" id="active_{{ $scholarship['id'] }}">
                </td>
                <td class="controls">
                    <a href="#" class="btn btn-sm btn-success p-0 px-2 save-scholarship" id="save_scholarship_{{ $scholarship['id'] }}"
                        data-sy_id="{{ $data['sy_id'] }}"
                        data-scholarship_id="{{ $scholarship['id'] }}">
                        <i class="fa fa-plus"></i>
                    </a>
                </td>
                <td class="controls">
                    <a href="#" class="btn btn-sm btn-danger p-0 px-2 delete-item float-left d-none" id="delete_btn_{{ $scholarship['id'] }}"
                        data-scholarship_id="{{ $scholarship['id'] }}">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        @endforeach

        @foreach ($data['offers'] as $offer)
            <tr>
                <td class="text-center">
                    <input type="text" class="form-control d-none" name="id" id="id_{{ $offer['scholarship_id'] }}" value="{{ $offer['id'] }}" readonly>
                    <span id="status_{{ $offer['scholarship_id'] }}">
                        <span class="badge bg-success">
                            <i class="fa fa-check"></i>
                        </span>
                    </span>
                </td>
                <td>{{ strtoupper($offer['scholarships']['description']) }}</td>
                <td>
                    <input type="date" class="form-control text-sm" name="date_from" id="date_from_{{ $offer['scholarship_id'] }}" value="{{ $offer['date_from'] }}">
                </td>
                <td>
                    <input type="date" class="form-control text-sm" name="date_to" id="date_to_{{ $offer['scholarship_id'] }}" value="{{ $offer['date_to'] }}">
                </td>
                <td class="text-center pt-2">
                    <input type="checkbox" name="active" id="active_{{ $offer['scholarship_id'] }}" @if($offer['active']) checked @endif>
                </td>
                <td class="controls">
                    <a href="#" class="btn btn-sm btn-warning p-0 px-2 save-scholarship" id="save_scholarship_{{ $offer['scholarship_id'] }}"
                        data-sy_id="{{ $offer['sy_id'] }}"
                        data-scholarship_id="{{ $offer['scholarship_id'] }}">
                        <i class="fa fa-save"></i>
                    </a>
                </td>
                <td class="controls">
                    <a href="#" class="btn btn-sm btn-danger p-0 px-2 delete-item float-left" id="delete_btn_{{ $offer['scholarship_id'] }}"
                        data-scholarship_id="{{ $offer['scholarship_id'] }}">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>