<link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css"/>

<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"
      media="screen">
<link rel="stylesheet" href="{{ assetV('/css/backend/updateBooking.css')}}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css"/>
<script type="text/javascript" src="{{ assetV('/js/partee.js')}}"></script>
<script type="text/javascript" src="{{ assetV('/js/backend/buzon.js')}}"></script>


  <script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
  <script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>

  <script src="/assets/js/notifications.js" type="text/javascript"></script>
  @include('backend.planning._bookScripts', ['update' => 1])

  <script type="text/javascript">
    $(document).ready(function () {
      $('#sendShareImagesEmail').click(function (event) {
        if (confirm('¿Quieres reenviar las imagenes')) {
          var email = $('#shareEmailImages').val();
          var register = $('#registerData').val();
          var roomId = $('#newroom').val();

          $.get('/admin/sendImagesRoomEmail', {email: email, roomId: roomId, register: register, returned: '1'},
                  function
                          (data) {
                    location.reload();
                  });
        }
      });

      function getScrollButton() {
        $('#chatbox').find("#chats").animate({scrollTop: $('#chats').prop("scrollHeight")}, 1000);
      }
      
            
      $('#chatbox').on('click','#loadchatboxMore',function (event) {
        var date = $(this).data('date');
        var _that = $(this);
        event.preventDefault();
        $.ajax({
          url: '/admin/book-logs/{{$book->id}}/'+date,
          cache: false
        }).done(function (data) {
          $('#chatbox').prepend(data);
          _that.remove();
        });
      });
      
      $('#loadchatbox').click(function () {
        $('#chatbox').load('/admin/book-logs/{{$book->id}}', getScrollButton);
      });
      setTimeout(function(){
        $('#chatbox').load('/admin/book-logs/{{$book->id}}', getScrollButton);
      },1000);
      $('#chatbox').on('click', '.see_more', function (event) {
        event.preventDefault();

        $.ajax({
          url: '/admin/book-logs/see-more/' + $(this).data('id'),
          cache: false
        })
                .done(function (data) {
                  var obj = $('#modal_seeLog');
                  obj.find('#msl_subj').text(data.subj);
                  obj.find('#msl_room').text(data.room);
                  obj.find('#msl_user').text(data.user);
                  obj.find('#msl_content').html(data.content);
                  obj.find('#msl_date').text(data.date);
                  obj.modal('show');
                });

      });
            $('#chatbox').on('click', '.see_more_mail', function (event) {
        event.preventDefault();

        $.ajax({
          url: '/admin/book-logs/see-more-mail/' + $(this).data('id'),
          cache: false
        })
                .done(function (data) {
                  var obj = $('#modal_seeLog');
                  obj.find('#msl_subj').text(data.subj);
                  obj.find('#msl_room').text(data.room);
                  obj.find('#msl_user').text(data.user);
                  obj.find('#msl_content').html(data.content);
                  obj.find('#msl_date').text(data.date);
                  obj.modal('show');
                });

      });
      $('.openFF').on('click', function (event) {
        event.preventDefault();
        var id = $(this).data('booking');
        $.post('/admin/forfaits/open', { _token: "{{ csrf_token() }}",id:id }, function(data) {
//          console.log(data);
          var formFF = $('#formFF');
          formFF.attr('action', data.link);
          formFF.find('#admin_ff').val(data.admin);
          formFF.submit();

        });
      });


 $('body').on('click','.sendSMS',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (that.hasClass('disabled-error')) {
          alert('Partee error.');
          return ;
        }
        if (that.hasClass('disabled')) {
//          alert('No se puede enviar el SMS.');
          return ;
        }
        $('#loadigPage').show('slow');
        that.addClass('disabled')
        $.post('/ajax/send-partee-sms', { _token: "{{ csrf_token() }}",id:id }, function(data) {
              if (data.status == 'danger') {
                window.show_notif('Partee Error:',data.status,data.response);
              } else {
                window.show_notif('Partee:',data.status,data.response);
                that.prop('disabled', true);
              }
              $('#loadigPage').hide('slow');
          });
        });
 $('body').on('click','.sendParteeMail',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (that.hasClass('disabled-error')) {
          alert('Partee error.');
          return ;
        }
        if (that.hasClass('disabled')) {
//          alert('No se puede enviar el SMS.');
          return ;
        }
        $('#loadigPage').show('slow');
        that.addClass('disabled')
        $.post('/ajax/send-partee-mail', { _token: "{{ csrf_token() }}",id:id }, function(data) {
              if (data.status == 'danger') {
                window.show_notif('Partee Error:',data.status,data.response);
              } else {
                window.show_notif('Partee:',data.status,data.response);
                that.prop('disabled', true);
              }
              $('#loadigPage').hide('slow');
          });
        });
        $('body').on('click','.showParteeLink',function(event) {
          $('#linkPartee').show(700);
        });
        

        var loadFF_resume = true;
        $('.showFF_resume').on('mouseover',function(){
          if (loadFF_resume){
            var tooltip = $(this).find('.FF_resume');
            tooltip.load('/admin/forfaits/resume-by-book/{{$book->id}}');
            loadFF_resume = false;
          }
        });
        
      
      window.onscroll = function() {fixedHeader()};
      var header = document.getElementById("headerFixed");
      var sticky = header.offsetTop;
      function fixedHeader() {
        if (window.pageYOffset > sticky) {
          header.classList.add("mobile-fixed");
        } else {
          header.classList.remove("mobile-fixed");
        }
      }
  
  
   $('body').on('click','.send_encuesta',function(event) {
        var id = $(this).data('id');
        var that = $(this);
        if (!confirm('Enviar encuesta por mail?')) {
          return ;
        }
        $('#loadigPage').show('slow');
        
        $.get('/admin/sendEncuesta/'+id, function(data) {
              if (data == 'ok') {
                window.show_notif('OK','success','Encuesta enviada');
                that.addClass('disabled')
                that.prop('disabled', true);
              } else {
                window.show_notif('Error','danger',data);
              }
              $('#loadigPage').hide('slow');
          });
      });
      
      
 $('body').on('change','.cc_upd',function(event) {
        var id = {{$book->id}};
        var idCustomer = {{$book->customer_id}};
        var cc_cvc = $('#cc_cvc').val();
        var cc_number = $('#cc_number').val();
        $('#loadigPage').show('slow');
        $.post('/admin/reservas/upd-visa', { _token: "{{ csrf_token() }}",id:id,idCustomer:idCustomer,cc_cvc:cc_cvc,cc_number:cc_number }, function(data) {
              if (data.status == 'success') {
                window.show_notif('Ok',data.status,data.response);
              } else {
                window.show_notif('Error:',data.status,data.response);
              }
              $('#loadigPage').hide('slow');
          });
        });
        
    });
  
  </script>
  