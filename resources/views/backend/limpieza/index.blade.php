<?php   use \Carbon\Carbon;
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts')
    <link href="/assets/css/font-icons.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css"
          media="screen">
    <link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css"/>
    <style>
        #TT_FyTwLBdBd1arY8FUjfzjDjjjD6lUMWzFrd1dEZi5KkjI3535G {
            margin: 10px auto;
        }
    </style>
@endsection

@section('content')
    <?php if (!$mobile->isMobile() ): ?>
    <div class="container  p-l-15 p-r-15 p-t-20 bg-white">
            <div class="row push-10">
                <div class="container">
                    <div class="col-xs-12 text-center">
                        <div class="col-md-4 col-md-offset-4 not-padding">
                            <h2 style="margin: 0;">
                                <b>Planning</b>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row push-20">
                <div class="col-md-7">
                    <button class="btn btn-success btn-tables btn-cons" type="button" data-type="checkin">
                        <span class="bold">Check IN</span>
                    </button>

                    <button class="btn btn-primary btn-tables btn-cons" type="button" data-type="checkout">
                        <span class="bold">Check OUT</span>
                    </button>
                </div>
                <div class="col-xs-12" id="resultSearchBook" style="display: none; padding-left: 0;"></div>
                <div class="col-xs-12 content-tables" style="padding-left: 0;">
                    @include('backend.planning._table', ['type'=> 'checkin'])
                </div>
            </div>
        </div>
    <?php endif; ?>
@endsection
@section('scripts')
    <script src="/assets/js/notifications.js" type="text/javascript"></script>
    <script>
    $(document).ready(function() {
        // Cargar tablas de reservas
        $('.btn-tables').click(function(event) {
            var type = $(this).attr('data-type');
            var year = $('#fechas').val();
            $.get('/admin/reservas/api/getTableData', { type: type, year: year }, function(data) {
                $('#resultSearchBook').empty();
                $('.content-tables').empty().append(data);
                $('.content-tables').show();

            });
        });


    });

    </script>
@endsection