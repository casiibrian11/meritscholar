<form action="" method="POST" id="form">
    <div class="modal-body">
        <p class="alert alert-info small">
            <strong><i class="fa fa-info-circle"></i> NOTE:</strong> Fields marked with <span class="required">*</span> are required.
        </p>
        @csrf
        <input type="hidden" name="id" id="id" value="{{ $data['category']['id'] ?? '' }}" readonly>
        <div class="form-floating mb-2">
            <input type="text" id="category_name" name="category_name" value="{{ $data['category']['category_name'] ?? '' }}" class="form-control uppercase" 
                placeholder="SCHOLARSHIP" autocomplete="off">
                <label for="category_name">SCHOLARSHIP CATEGORY <span class="required">*</span></label>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn close btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-save">Save</button>
    </div>
</form>