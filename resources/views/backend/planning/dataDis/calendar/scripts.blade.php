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
      $('.calendarBox').on('click', '.reloadCalend', function (Event) {
          var time = $(this).attr('data-time');
          window.cal_move = false;
          $('.calendarBox').empty().load(
            window.URLCalendar + time,
            function () {
                setTimeout(function () {
                    moveCalendar();
                }, 700);
            }
          );
          Event.stopPropagation()
      });
      $('.calendarBox').on('click', '.getMonths', function (Event) {
        $('.calendarBox').empty().load(
            window.URLCalendar + time,
            function () {
                setTimeout(function () {
                    moveCalendar();
                }, 700);
            }
          );
      });


      // Cargamos el calendario cuando acaba de cargar la pagina
      setTimeout(function () {
          $('.calendarBox').empty().load(window.URLCalendar,
            function () {
                setTimeout(function () {
                    moveCalendar();
                }, 700);
            });
      }, 500);
        });
</script>