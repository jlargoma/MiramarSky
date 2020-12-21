<?php
global $loadScript;

if (!$loadScript):
  $loadScript = true;
?>
<script type="text/javascript" src="{{ assetV('/js/datePicker01.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="{{ assetV('/js/backend/booking.js')}}"></script>
<script type="text/javascript">
   window["csrf_token"] = "{{ csrf_token() }}";
   window["uRole"] = "{{ $uRole }}";
</script>
<script src="{{ assetV('/js/backend/booking_script.js')}}" type="text/javascript"></script>
<?php
endif;
?>