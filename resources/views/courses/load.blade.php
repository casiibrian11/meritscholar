<label for="course_id">COURSE</label>
<select name="course_id" id="course_id" class="w-100">
    <option value=""></option>
    @if (count($data['courses']) > 0)
        <option value="" selected>SELECT COURSE</option>
        @foreach ($data['courses'] as $course)
            <option value="{{ $course['id'] }}">
                {{ strtoupper($course['course_name']) }}
            </option>
        @endforeach
    @endif
</select>

<script>
    $(function() {
        $('#course_id').select2({
            width: 'resolve'
        });
    });
</script>