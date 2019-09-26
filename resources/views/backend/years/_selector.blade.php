<?php $years = \App\Years::all(); ?>
<select id="years" class="form-control minimal" <?php if ( Auth::user()->role == "agente"):?>disabled<?php endif ?>>
    @foreach($years as $key => $year)
        <option value="{{ $year->id }}" @if ($year->active == 1) selected @endif >
            {{ $year->year }} - {{ $year->year + 1 }}
        </option>
    @endforeach
</select>
<script type="text/javascript">
        $(document).ready(function () {

                $('#years').change(function () {
                        var yearId = $(this).val();
                        $.post("{{ route('years.change') }}", { year: yearId }).done(function( data ) {
                                console.log(data);
                                window.location = window.location.href.split("#")[0];
//                                location.reload();
                        });
                });
        });
</script>