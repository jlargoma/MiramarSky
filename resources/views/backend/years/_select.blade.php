<?php $years = \App\Years::all(); ?>
<select name="years" class="form-control minimal s_years">
    @foreach($years as $key => $year)
        <option value="{{ $year->id }}" @if ($year->active == 1) selected @endif >
            {{ $year->year }} - {{ $year->year + 1 }}
        </option>
    @endforeach
</select>
