<div class="row mt-5">
    <div class="col-sm-12">
        <label class="text-sm">File Type (required file type) <span class="required">*</span></label>
        <select class="file_type" name="file_type[]" multiple="multiple" style="width:100% !important;" required>
            @if ($data['type'] == 'image')
                <option value="png">PNG</option>
                <option value="jpg">JPG</option>
                <option value="jpeg">JPEG</option>
                <option value="gif">GIF</option>
            @else
                <option value="word">Word Document</option>
                <option value="pdf">PDF</option>
                <option value="excel">Excel</option>
            @endif
        </select>
    </div>
</div>

@if ($data['type'] == 'image')
<div class="row mt-2">
    <div class="col-sm-12">
        <b class="text-sm">Sample</b>
        <input type="file" id="sample" name="sample" class="form-control mt-2" autocomplete="off">
    </div>
</div>
@endif


<script>
    $(function() {
        $('.file_type').select2({
            width: 'resolve'
        });
    });
</script>