<script type="text/javascript">
  $(document).ready(function () {
    window["cal_move"] = false;

    var moveCalendar = function () {
      if (window.cal_move)
        return;
      window.cal_move = true;
     
      
      var target = $('#btn-active').attr('data-month');
      var targetPosition = $('.contentCalendar #month-' + target).position();
      $('.contentCalendar').animate({scrollLeft: "+=" + targetPosition.left + "px"}, "slow");
      
    }



    window["moveCalendar"] = moveCalendar;
    
//   $('#btn-active').trigger('click');

    $('.content-calendar').on('click', '.reloadCalend', function (Event) {
      var time = $(this).attr('data-time');
      window.cal_move = false;
      $('.content-calendar').empty().load(
              window.URLCalendar + time,
              function () {
                setTimeout(function () { moveCalendar();},700);
//                moveCalendar();
              }
      );
      Event.stopPropagation()
    });



    // Ver imagenes por piso

    $('body').on('click','.getImages',function (event) {
      var idRoom = $(this).attr('data-id');
      $.get('/admin/rooms/api/getImagesRoom/' + idRoom, function (data) {
        $('#modalRoomImages .modal-content').empty().append(data);
      });
    });

    // Cargamos el calendario cuando acaba de cargar la pagina
    setTimeout(function () {
      $('.content-calendar').empty().load('/getCalendarMobile',
              function () {
                setTimeout(function () { moveCalendar();},700);
              });
    }, 1500);

  });


</script>

