{!! $content!!}
<script type="text/javascript">
  $(document).ready(function () {

      $('.calendarBox').on('click','.ddd',function (event) {
          var td = $(this).closest('td');
          var rID = td.data('rid');
          if (typeof rID != 'undefined'){
            $('.datadis_day').load('/admin/dataDis/day/'+rID+'/'+td.data('day'))
          }
      });
      
  });
</script>
<style>
  .calLink {
    background-color: #0173ff;
    color: #dadada;
    font-weight: bold;
    text-align: center;
  }

  .btn-fechas-calendar {
    color: #fff;
    background-color: #899098;
  }
  #btn-active {
    background-color: #10cfbd;
  }


  .content-calendar .tip:hover .end,
  .content-calendar .tip:hover .start,
  .content-calendar .tip:hover .total{
    background-color: red !important;
  }

  .content-calendar .td-calendar{
    border:1px solid grey;
    width: 24px;
    height: 20px;
  }
  .content-calendar .no-event{
    border:1px solid grey;
    width: 24px;
    height: 20px;
  }
  .content-calendar .ev-doble{
    border:1px solid grey;
    width: 24px;
    height: 20px;
  }
  .content-calendar .start{
    width: 45%;
    float: right;
    cursor: pointer;
  }
  .content-calendar .end{
    width: 45%;
    float: left;
    cursor: pointer;
  }
  .content-calendar .total{
    width: 100%;
    height: 100%;
    cursor: pointer;
  }
  .content-calendar .td-month{
    border:1px solid black;
    width: 24px;
    height: 20px;
    font-size: 10px;
    padding: 5px!important;
    text-align: center;
    min-width: 25px;
  }

  .boxDaily{
    padding: 0 3em;
    background-color: #FFF;
    box-shadow: 1px 2px 3px #3e3e3e;
    margin-top: -44px;
  }
  .table-responsive.contentCalendar {
    padding: 0 2em 0 25px;
  }
  
</style>