@extends('layouts.admin-master')

@section('title') STRIPE CONNECT - Administrador de reservas MiramarSKI @endsection

@section('externalScripts')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="container-fluid padding-25 sm-padding-10">
        <div class="row push-20">
            <div class="col-md-12 col-xs-12 text-center">
                <h2 style="letter-spacing: -1px; text-transform: uppercase">Stripe <span
                            class="font-w800">connect</span></h2>
            </div>
        </div>
        <div class="row push-20">
            <div class="col-md-6 col-xs-12 text-left">
                <button class="btn btn-primary" type="button" id="createTransfer">
                    <i class="fa fa-plus"></i> Transferencia
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 col-xs-12 text-center" id="content-tableOwned"
                 style="max-height: 800px; overflow: auto">
                @include('backend.stripeConnect._tableOwneds')
            </div>
            <div class="col-md-7 col-xs-12 text-center" id="contentFormTransfer">
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/assets/js/notifications.js" type="text/javascript"></script>
    <script>
      $('.editable').change(function (event) {
        var id   = $(this).attr('data-id');
        var iban = $(this).val();
        $.post("/admin/stripe-connect/create-account-stripe-connect", {user: id, iban: iban}).done(function
         (data) {
            $('#content-tableOwned').empty().load('/admin/stripe-connect/load-table-owneds');
            $.notify({
              title: '<strong>Perfecto</strong>, ',
              icon: 'glyphicon glyphicon-star',
              message: 'La cuenta se ha creado correctamente'
            }, {
              type: 'success',
              animate: {
                enter: 'animated fadeInUp',
                exit: 'animated fadeOutRight'
              },
              placement: {
                from: "top",
                align: "left"
              },
              offset: 80,
              spacing: 10,
              z_index: 1031,
              allow_dismiss: true,
              delay: 1000,
              timer: 1000,
            });
        });

      });
      $('#createTransfer').click(function (event) {
        var arr = [];
        var count = 0;
        $('.checkToTransfer').each(function (index) {
          if ($(this).is(':checked')) {
            arr.push($(this).val());
            count++;
          }
        });
        if (count > 0) {
          $.post("/admin/stripe-connect/load-transfer-form", {owneds: JSON.stringify(arr)}).done(function (data) {
            $('#contentFormTransfer').empty().append(data);
          });
        }
        else {
          $.notify({
            title: '<strong>Cuidado</strong>, ',
            icon: 'glyphicon glyphicon-star',
            message: 'No hay propietarios seleccionados para realizar la transferencia'
          }, {
            type: 'warning',
            animate: {
              enter: 'animated fadeInUp',
              exit: 'animated fadeOutRight'
            },
            placement: {
              from: "top",
              align: "left"
            },
            offset: 80,
            spacing: 10,
            z_index: 1031,
            allow_dismiss: true,
            delay: 1000,
            timer: 1000,
          });
        }


      });
      /**/

    </script>
@endsection