<?php $years = \App\Years::all(); ?>
<div class="row push-10">
    <div class="@if(!$minimal) container @else col-md-12 @endif">
        <div class="col-xs-12 text-center">
            <div class="@if(!$minimal) col-md-2 @else col-md-4 @endif @if(!$minimal) col-md-offset-3 @endif
            not-padding">
                <h2 style="margin: 0;" @if($minimal)class="text-left" @endif>
                    <b>@if($minimal) Año Actual @else Planning @endif</b>
                </h2>
            </div>
            <div class="@if(!$minimal) col-md-2 @else col-md-4 @endif">
                <select id="years" class="form-control minimal" <?php if ( Auth::user()->role == "agente"):
                ?>disabled<?php endif ?>>
                    @foreach($years as $key => $year)
                        <option value="{{ $year->id }}" @if ($year->active == 1) selected @endif >
			                {{ $year->year }} - {{ $year->year + 1 }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        
        $('#years').change(function () {
            var yearId = $(this).val();
            $.post("{{ route('years.change') }}", { year: yearId }).done(function( data ) {
               console.log(data);
               location.reload();
            });
        });
    });
</script>