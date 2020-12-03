$(document).ready(function () {
window['dateRangeObj'] = {
    "buttonClasses": "button button-rounded button-mini nomargin",
    "applyClass": "button-color",
    "cancelClass": "button-light",
    autoUpdateInput: true,
    locale: {
      firstDay: 1,
      format: 'DD/MM/YYYY',
      "applyLabel": "Aplicar",
      "cancelLabel": "Cancelar",
      "fromLabel": "From",
      "toLabel": "To",
      "customRangeLabel": "Custom",
      "daysOfWeek": [
        "Do",
        "Lu",
        "Mar",
        "Mi",
        "Ju",
        "Vi",
        "Sa"
      ],
      "monthNames": [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
      ],
    },

  };
$(".daterange01").daterangepicker(window.dateRangeObj);
window.dateRangeObj.locale.format = 'DD MMM, YY';
$(".daterange02").daterangepicker(window.dateRangeObj);
window.dateRangeObj.locale.format = 'DD/MM/YYYY';


  Date.prototype.ddmmmyyyy = function () {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();
    return [
      (dd > 9 ? '' : '0') + dd,
      (mm > 9 ? '' : '0') + mm,
      this.getFullYear()
    ].join('/');
  };
  Date.prototype.yyyymmmdd = function () {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();
    return [
      this.getFullYear(),
      (mm > 9 ? '' : '0') + mm,
      (dd > 9 ? '' : '0') + dd
    ].join('-');
  };
  var render_yyyymmmdd = function (dates) {
    var date = dates.trim().split('/');
    return date[2] + '-' + date[1] + '-' + date[0];
  };
});