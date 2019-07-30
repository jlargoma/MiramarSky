
(function ($) {
  "use strict";
  var today = new Date();
  console.log('asdfasd');
    $("#start_date").datepicker({
      closeText: 'Cerrar',
      prevText: '<Ant',
      nextText: 'Sig>',
      currentText: 'Hoy',
      monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
      monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
      dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
      weekHeader: 'Sm',
      dateFormat: 'yy-mm-dd',
      firstDay: 1,
      isRTL: false,
      showMonthAfterYear: false,
      yearSuffix: '',
      beforeShowDay: function (date) {
        var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
        return [(date > today)];
//        return [wp_pdg_booking.days.indexOf(string) >= 0]
      },
      onSelect: function (date) {
        showDate(date)
      },
    });


  function showDate(date) {
//    var paramts = {
//      date: date,
//      pid: wp_pdg_booking.pid,
//      security: wp_pdg_booking.security,
//      action: 'booking_getTimes'
//    };
//    $("#booking_times").text('...');
//    $("#booking_times").load(wp_pdg_booking.ajaxurl, paramts,function(){pgd_create_slider(); }).prop("disabled", false);
//    $("#save_appointment").prop("disabled", true);
    
  }
 
})(jQuery);