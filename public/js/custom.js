$(document).on('mouseover','[data-toggle="tooltip"]',function(){
    $(this).tooltip();
});

$(document).ready(function() { 
  window["show_notif"] = function(title,status,message){
    $.notify({
          title: '<strong>'+title+'</strong>, ',
          icon: 'glyphicon glyphicon-star',
          message: message
      },{
          type: status,
          animate: {
              enter: 'animated fadeInUp',
              exit: 'animated fadeOutRight'
          },
          placement: {
              from: "top",
              align: "left"
          },
          offset: 20,
          spacing: 10,
          z_index: 1031,
          allow_dismiss: true,
          delay: 1000,
          timer: 3000,
      }); 
    }
  
   
  window["formatDate"] = function (date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
  }
});
