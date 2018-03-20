<?php   use \Carbon\Carbon;
setlocale(LC_TIME, "ES"); 
setlocale(LC_TIME, "es_ES"); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="{{ asset('/frontend/css/bootstrap.css') }}" media="all" />
    <script type="text/javascript" src="{{ asset('/frontend/js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/frontend/js/bootstrap.css') }}"></script>
    <style type="text/css">
    #main{
        padding:12px;

    }
    .offset-margin{
        margin-top:-10px;
    }
    td{
        padding-left:10px;
    }
    .description{
        min-height:120px;
        margin-bottom:90px;
    }
    .blank{
        border-top:solid 1px #eee;
        border-left:solid 1px #eee;
        min-height:120px;
        padding:0px !important;
    }
    .panel-body{
        padding-bottom:0 !important;
    }
    .content{
        margin-top:40px; 
        padding-bottom:20px !important;
    }
    .table{
        margin-bottom: 0px !important;
    }
</style>
</head>
<body>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-lg-offset-2">
            <div class="panel panel-default main">

                <div class="panel-body">

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <h1>Factura #SN<?php echo Carbon::CreateFromFormat('Y-m-d',$invoice->start)->format('y'); ?><?php echo $invoice->id ?></h1>
                            <div class="col-lg-6 col-md-6 col-xs-12">
                               <img src="http://miramarski.com/img/miramarski/logo_isde.png" class="img-responsive" style="max-width: 300px;">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 pull-right" style="padding-top: 40px; padding-bottom: 10px;">

                            <b><?php echo $invoice->name_business ?></b><br>
                            <b><?php echo $invoice->nif_business ?></b><br>
                            <b><?php echo $invoice->address_business ?></b><br>
                            <b><?php echo $invoice->zip_code_business ?></b><br>
                        </div>
                    </div>
                    <!--row-->

                    <hr>
                    <div class="row">
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            <h3><?php echo ucfirst($invoice->name) ?></h3>
                            <p class="offset-margin">DNI: <?php echo $invoice->nif ?></p>
                            <p class="offset-margin">Dirección: <?php echo $invoice->address ?></p>
                            <p class="offset-margin">C. Postal: <?php echo $invoice->postalcode ?></p>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3">
                        
                        </div>
                    </div>
                    <!--row-->


                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 content">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h1 class="panel-title">Detalles de la factura</h1>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class=" col-lg-12 col-md-12 col-sm-12 description">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Descripción</th>
                                                        <th>Importe</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            Alojamiento en apartamento edificio MiramarSKI<br>
                                                            <b>Fechas: del <?php echo Carbon::CreateFromFormat('Y-m-d',$invoice->start)->formatLocalized('%d %B %Y'); ?> al <?php echo Carbon::CreateFromFormat('Y-m-d',$invoice->finish)->formatLocalized('%d %B %Y'); ?></b>
                                                        </td>
                                                        <td>
                                                            <?php if($invoice->total_price == null || $invoice->total_price == "") { $invoice->total_price = 0; } ?>

                                                             <b><?php echo number_format($invoice->total_price, 2, ',','.') ?>€</b>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>


                                        </div>

                                        <div class="col-lg-8 pull-left" style="border-top:solid #ddd 1px;"></div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 pull-right blank">

                                            <table class="table table bordered">
                                                <tbody>
                                                    <tr>
                                                        <td><label>B.imponible:</label><span class="pull-right"> <b><?php echo number_format(($invoice->total_price - ($invoice->total_price * 0.10)), 2, ',','.') ?>€</b></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Impuestos:</label><span class="pull-right"> <b><?php echo number_format( ($invoice->total_price * 0.10), 2, ',','.') ?>€</b></span></td>
                                                    </tr>
                                                    <tr style="background-color:#eee;">
                                                        <td><label>TOTAL:</label><span class="pull-right"> <b><?php echo number_format($invoice->total_price, 2, ',','.') ?>€</b></span></td>
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                    <!--row-->
                                </div>

                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>
</body>
</html>

