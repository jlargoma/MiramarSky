{!! $content!!}
<script type="text/javascript">
 $(document).ready(function() {
   var cal_move = false;
   var moveCalendar = function(){
      if(cal_move) return;
      cal_move = true;
      $('.btn-fechas-calendar').css({
      'background-color': '#899098',
      'color': '#fff'
      });
      $('#btn-active').css({
        'background-color': '#10cfbd',
        'color': '#fff'
      });
      var target = $('#btn-active').attr('data-month');
      var targetPosition = $('.content-calendar #month-' + target).position();
      $('.content-calendar').animate({scrollLeft: "+=" + targetPosition.left + "px"}, "slow");
   }
    setTimeout(function () { moveCalendar();},200);
//   $('#btn-active').trigger('click');

  $('.content-calendar').on('click','.reloadCalend', function(){
    var time = $(this).attr('data-time');
    cal_move = false;
    $('.content-calendar').empty().load(
            window.URLCalendar+time, 
            function(){ moveCalendar();}
            );
  });



  // Ver imagenes por piso

  $('.getImages').click(function (event) {
    var idRoom = $(this).attr('data-id');
    $.get('/admin/rooms/api/getImagesRoom/' + idRoom, function (data) {
      $('#modalRoomImages .modal-content').empty().append(data);
    });
  });
 
  $('.content-calendar .tip').on('click','.calLink',function(event){
    location.href = $(this).data('href');
  });
  $('.content-calendar .tip').hover(function(event){
    console.log(event.screenY,event.pageY,screen.width);
    if (screen.width<768){
      $(this).find('span').css('top','auto');
      $(this).find('span').css('bottom','-9px');
      $(this).find('span').css('left', 'auto');
      $(this).find('span').css('right', '3px');
    
    } else {
      $(this).find('span').css('top', (event.screenY-120));
      $(this).find('span').css('left', (event.pageX-100));
    }
  });
   });
</script>
<style>
  .content-calendar .td-calendar{
    border:1px solid grey;width: 24px; height: 20px;
  }
  .content-calendar .no-event{
    border:1px solid grey;width: 24px; height: 20px;
  }
  .content-calendar .ev-doble{
    border:1px solid grey;width: 24px; height: 20px;
  }
  .content-calendar .start{
    width: 45%;float: right; cursor: pointer;
  }
  .content-calendar .end{
    width: 45%;float: left; cursor: pointer;
  }
  .content-calendar .total{
    width: 100%;height: 100%; cursor: pointer;
  }
  .content-calendar .td-month{
    border:1px solid black;width: 24px; height: 20px;font-size: 10px;padding: 5px!important;
    text-align: center;
    min-width: 25px;
  }
  .calLink {
    background-color: #0173ff;
    color: #dadada;
    font-weight: bold;
    text-align: center;
  }
</style>
  