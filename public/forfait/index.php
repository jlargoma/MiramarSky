<!DOCTYPE html>
<html dir="ltr" lang="es-ES">
<head>
   <title>Alquiler de apartamentos de lujo Miramarski</title>
   
   <meta http-equiv="content-type" content="text/html; charset=utf-8" />

   <!-- Stylesheets
   ============================================= -->
   <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
   <link rel="stylesheet" href="../css/app.css" type="text/css" />

   <link rel="stylesheet" href="../frontend/css/dark.css" type="text/css" />
   <link rel="stylesheet" href="../frontend/css/responsive.css" type="text/css" />
   <meta name="viewport" content="width=device-width, initial-scale=1" />

   <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   <link href="https://cdn.rawgit.com/michalsnik/aos/2.1.1/dist/aos.css" rel="stylesheet">
   <link rel="stylesheet" href="../frontend/css/components/daterangepicker.css" type="text/css" />

    <!-- Plugin for date picker 
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/latest/css/bootstrap.css"/>-->
    <link rel="stylesheet" type="text/css" href="css/datepicker.css"/>
    <!--<link rel="stylesheet" href="css/card.css" type="text/css" />-->
    
   <!-- Document Title
   ============================================= -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
    <!--<script type="text/javascript" src="js/jquery.card.js"></script>-->
    
    <style type="text/css">
        div.clase{
           cursor: pointer;
           padding: 0 10px;
        }
        div.forfait{
           cursor: pointer;
           padding: 0 10px;
        }
        .title-forfait{
            line-height: 1;
           font-size: 16px;
           text-transform: uppercase;
           font-weight: 300;
        }
        #Forfait0, #Forfait1, #Forfait2, #Forfait3, #Forfait4, #Forfait5{
           padding: 0 15px;
        }
        h2.text-center.font-w300.text-black.black{
           line-height: 1; 
           text-transform: uppercase;
        }
        .fancy-title.title-bottom-border h1, .fancy-title.title-bottom-border h2, .fancy-title.title-bottom-border h3, .fancy-title.title-bottom-border h4, .fancy-title.title-bottom-border h5, .fancy-title.title-bottom-border h6{
           border-bottom: 1px solid #00b5ec!important;
        }

        .input_required{
            color:red;
        }
        
        .request_row{
            background: rgb(255,255,255,0.4);
            border: 1px solid #FFFFFF;
            margin-bottom: 8px !important;
            padding-bottom: 8px;
            padding-top: 8px;
        }
        
        .requests_header{
            margin: 8px 0 0 0 !important;
        }
        
        .requests_header, #requests_header{
            background: rgb(255,255,255,0.8);
            padding-bottom: 8px;
        }
        
        .requests_label{
            color:#000 !important;
            font-weight: initial !important;
            font-family: Roboto,sans-serif !important;
            font-size:12px;
        }

        div#requests_button button{
            width:100px;
            margin-bottom:16px;
        }
        
        div#requests_container{
            margin-left: 6px;
            padding-right: 0;
        }

        .border_blue{
            border: 1px solid dodgerblue;
        }
        
        .mobile{
            display:none;
        }
        .desktop{
            display:block;
        }
        
        @media (max-width: 991px) {
            .mobile{
                display:block;
            }
            .desktop{
                display:none;
            }
        }
    </style>
</head>

<body class="stretched no-transition" >

    <!-- Document Wrapper=============================================-->
    <div id="wrapper" class="clearfix" style="padding-bottom: 0px">
        <!-- Form for Reserve forfait2
        ============================================-->  
    <form role="form" id='form' method="post" action="/solicitudForfait" style="margin-bottom: 0px">
      <section id="content" style="background-image: url('../img/fortfait/background.jpg') !important; background-repeat: no-repeat; background-size: cover;">
          <div class="content-wrap nopadding">
              <div id="section-works"  class="full-screen" style="padding-top:5px;overflow: scroll">
                  <div class="col-md-10 col-lg-6 col-lg-offset-3 col-md-offset-1 sin-margin-mobile" style="padding:0!important;">
                      <div id='calendar-content' class='container-fluid'>
                         
                              <div class="panel panel-default" style="background-color: rgba(255,255,255,0.75);">
                                  <div class="panel-body" >
                                    <!-- Cabecera -->
                                       <div class="heading-block fancy-title nobottomborder title-bottom-border col-xs-12">
                                          <h2 class="black font-w800 center t400 ls1 push-20 " style="font-size:23px; letter-spacing: -2px">
                                             Petición ForFaits y Clases
                                          </h2>
                                       </div>
                                       
                                         <div class="form-group col-sm-4 col-xs-12">
                                             <span>*Nombre</span>
                                             <input type="text" class="sm-form-control" name="nombre" id="nombre" placeholder="Introduce aqui tu Nombre" maxlength="40" required>
                                         </div>

                                         <div class="form-group col-sm-4 col-xs-12">
                                             <span>*Email</span>
                                             <input type="email" class="sm-form-control" name="email" id="email" placeholder="Aquí pon tu email" maxlength="40" required>
                                         </div>

                                         <div class="form-group col-sm-4 col-xs-12">
                                             <span>*Telefono</span>
                                             <input type="text" class="sm-form-control" name="telefono" id="telefono" placeholder="Aquí pon el telefono" maxlength="9" required>
                                         </div>

<!--                                         <div class="form-group col-md-6 col-sm-offset-3 col-xs-12 push-30">
                                          <h4 class="text-center push-0">* Fecha de tus Forfaits</h4>
                                             <div class='input-group'>
                                                 <input class="sm-form-control" type="text" name="date-entrada" id="date-entrada" name="example-daterange1" placeholder="Desde" maxlength="10" readonly="readonly" required>
                                                 <span class="input-group-addon" id="btnCalendarEntrada"><span class="icon-calendar"></span></span>
                                                 <input class="sm-form-control" type="text" name="date-salida" id="date-salida" name="example-daterange1" placeholder="Hasta" maxlength="10" readonly="readonly" required>
                                             </div>
                                         </div>-->

                                    <!-- Forfaits -->

                                       <div class="heading-block fancy-title nobottomborder title-bottom-border col-xs-12">
                                          <h2 class="black font-w800 center t400 ls1 push-20 " style="color:white;font-size:23px">
                                             ForFaits
                                          </h2>
                                       </div>
                                    
                                        <h4 class="text-center push-0">* Seleccione tus días de esquí</h4><br/>

                                        <div class="form-group col-md-12 col-xs-12 push-10 border_blue desktop">
                                            <div class="form-group col-md-12 col-xs-12 push-10 requests_header">
                                                <div class="col-lg-2"></div>
                                                <div class="col-lg-3">*Primer día de Esquí</div>
                                                <div class="col-lg-3">*Última día de Esquí</div>
                                                <div class="col-lg-2">Días</div>
                                                <div class="col-lg-2">*Elige Nº Forfaits</div>
                                            </div>
                                            <div id="requests_header" class="form-group col-md-12 col-xs-12 push-10">
                                                <div class="col-lg-2">*Seleccione</div>
                                                <div class="col-lg-3">
                                                    <input class="sm-form-control datepicker_init_start_date" type="text" name="date-entrada" id="date-entrada" name="example-daterange1" placeholder="Desde" maxlength="10" required>
                                                </div>
                                                <div class="col-lg-3">
                                                    <input class="sm-form-control datepicker_init_end_date" type="text" name="date-salida" id="date-salida" name="example-daterange1" placeholder="Hasta" maxlength="10" required>
                                                </div>
                                                <div class="col-lg-2">
                                                    <input type="text" name="request_days" class="form-control request_days" placeholder="0" disabled="disabled">
                                                </div>
                                                <div class="col-lg-2">
                                                    <select id="forfatis_number" class="form-control" name="forfatis_number">
                                                        <?php
                                                            for($x=0;$x<=10;$x++){
                                                                echo '<option value="'.$x.'">'.$x.'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-12 col-xs-12 push-10 border_blue mobile">
                                            <div id="requests_header" class="form-group col-md-12 col-xs-12 push-10" style="margin: 8px 0 8px 0 !important;">
                                                <div class="col-xs-12">*Primer día de Esquí</div>
                                                <div class="col-xs-12">
                                                    <input class="sm-form-control datepicker_init_start_date" type="text" name="date-entrada" id="date-entrada" name="example-daterange1" placeholder="Desde" maxlength="10" required>
                                                </div>
                                                <div class="col-xs-12">*Última día de Esquí</div>
                                                <div class="col-xs-12">
                                                    <input class="sm-form-control datepicker_init_end_date" type="text" name="date-salida" id="date-salida" name="example-daterange1" placeholder="Hasta" maxlength="10" required>
                                                </div>
                                                <div class="col-xs-12">Días</div>
                                                <div class="col-xs-12">
                                                    <input type="text" name="request_days" class="form-control request_days" placeholder="0" disabled="disabled">
                                                </div>
                                                <div class="col-xs-12">*Elige Nº Forfaits</div>
                                                <div class="col-xs-12">
                                                    <select id="forfatis_number" class="form-control" name="forfatis_number">
                                                        <?php
                                                            for($x=0;$x<=10;$x++){
                                                                echo '<option value="'.$x.'">'.$x.'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>                                        

                                        <div id="requests_container" class="form-group col-md-12 col-xs-12 push-10">

                                        </div>
                                        
                                        <div id="requests_button" class="text-center" style="display:none;">
                                            <button name="boton" id="request_button" class="btn btn-success form-control price_request" type="button">Solicitar</button>
                                        </div>


                                        <div id="request_row_div" class="form-group col-md-12 col-xs-12 push-10 desktop" style="display:none;">
                                            <div class="form-group col-md-12 col-xs-12 push-10 request_row row">

                                                <div class="col-lg-2">
                                                    <div class="col-lg-12">
                                                        <label class="requests_label">&nbsp;</label>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <i class="fa fa-user" aria-hidden="true"></i> <span>Forfait <span class="forfait_number"></span></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label class="requests_label">*Primer día de Esquí</label>
                                                    <input class="sm-form-control datepicker_init_start_date" type="text" name="date-entrada-template" id="date-entrada" name="example-daterange1" placeholder="Desde" maxlength="10" required>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label class="requests_label">*Última día de Esquí</label>
                                                    <input class="sm-form-control datepicker_init_end_date" type="text" name="date-salida-template" id="date-salida" name="example-daterange1" placeholder="Hasta" maxlength="10" required>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label class="requests_label">Días</label>
                                                    <input type="text" name="request_days" class="form-control request_days" placeholder="0" disabled="disabled">
                                                </div>
                                                <div class="col-lg-2">
                                                    <label class="requests_label">Edad</label>
                                                    <input type="number" name="request_years" class="form-control request_years" placeholder="0">
                                                </div>
                                            </div>
                                        </div>

                                        <div id="request_row_div" class="form-group col-md-12 col-xs-12 push-10 mobile" style="display:none;">
                                            <div class="form-group col-md-12 col-xs-12 push-10 request_row row">
                                                <div class="col-lg-2">
                                                    <div class="col-lg-12">
                                                        <label class="requests_label">&nbsp;</label>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <i class="fa fa-user" aria-hidden="true"></i> <span>Forfait <span class="forfait_number"></span></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label class="requests_label">*Primer día de Esquí</label>
                                                    <input class="sm-form-control datepicker_init_start_date" type="text" name="date-entrada-template" id="date-entrada" name="example-daterange1" placeholder="Desde" maxlength="10" required>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label class="requests_label">*Última día de Esquí</label>
                                                    <input class="sm-form-control datepicker_init_end_date" type="text" name="date-salida-template" id="date-salida" name="example-daterange1" placeholder="Hasta" maxlength="10" required>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label class="requests_label">Días</label>
                                                    <input type="text" name="request_days" class="form-control request_days" placeholder="0" disabled="disabled">
                                                </div>
                                                <div class="col-lg-2">
                                                    <label class="requests_label">Edad</label>
                                                    <input type="number" name="request_years" class="form-control request_years" placeholder="0">
                                                </div>
                                            </div>
                                        </div>

                                    <!-- Selector Forfaits -->
<!--                                         <div class="col-sm-12 push-20 label_hide">
                                            <div class="col-md-2 col-xs-6 forfait" data-value="0">
                                             <div class="col-xs-12 not-padding">
                                                <img src="../img/fortfait/juvenil.jpg" class="img-responsive push-10"/>
                                                <h3 class="text-center title-forfait push-5">
                                                   Forfait Juvenil
                                                </h3>
                                             </div>
                                            </div>
                                            <div class="col-md-2 col-xs-6 forfait" data-value="1">
                                             <div class="col-xs-12 not-padding">
                                                <img src="../img/fortfait/junior.jpg" class="img-responsive push-10"/>
                                                <h3 class="text-center title-forfait push-5">
                                                   Forfait Junior
                                                </h3>
                                             </div>
                                            </div>
                                            <div class="col-md-2 col-xs-6 forfait" data-value="2">
                                             <div class="col-xs-12 not-padding">
                                                <img src="../img/fortfait/adulto.jpg" class="img-responsive push-10"/>
                                                <h3 class="text-center title-forfait push-5">
                                                   Forfait Adulto
                                                </h3>
                                             </div>
                                            </div>
                                            <div class="col-md-2 col-xs-6 forfait" data-value="3">
                                             <div class="col-xs-12 not-padding">
                                                <img src="../img/fortfait/senior.jpg" class="img-responsive push-10"/>
                                                <h3 class="text-center title-forfait push-5">
                                                   Forfait Senior
                                                </h3>
                                             </div>
                                            </div>
                                            <div class="col-md-2 col-xs-6 forfait" data-value="4">
                                             <div class="col-xs-12 not-padding">
                                                <img src="../img/fortfait/juvenil-familiar.jpg" class="img-responsive push-10"/>
                                                <h3 class="text-center title-forfait push-5">
                                                   Forfait Juvenil Familia
                                                </h3>
                                             </div>
                                            </div>
                                            <div class="col-md-2 col-xs-6 forfait" data-value="5">
                                             <div class="col-xs-12 not-padding">
                                                <img src="../img/fortfait/junior_familiar.jpg" class="img-responsive push-10"/>
                                                <h3 class="text-center title-forfait push-5">
                                                   Forfait Junior Familiar
                                                </h3>
                                             </div>
                                            </div>
                                         </div>

                                         <div class="form-group col-sm-12 col-xs-12">
                                          
                                           Juvenil 
                                                <div id="Forfait0" class="row desc" style="display: none;border-left: solid;border-right: solid">
                                                   <div class="col-md-12 col-xs-12" style="margin-bottom: 20px;">
                                                      <div class="col-md-3 col-xs-12">
                                                         <img src="../img/fortfait/juvenil.jpg" class="img-responsive" style="margin-top: 30px;">
                                                      </div>
                                                      <div class="col-md-9 col-xs-12">
                                                         <h2 class="text-center font-w300 text-black black" >Reserva <span class="font-w800">Forfait Juvenil</span></h2>
                                                         Ahora, al alquilar cualquier tu equipo puedes reservar tu Forfait Express, para que lo tengas preparado cuando entres en tu apartamento .<br /><br />

                                                         El precio varía según la temporada Baja/Alta/Primavera o en Promoción, por lo que tan solo tienes que hacer la reserva y ya efectuaras el pago una vez que se pongan en contacto contigo .<br /><br />

                                                         <strong>NOTA: Para reservar tu Forfait, es obligatorio enviar este formulario con  3-4 dias de antelación, de lo contrario, no se tramitará el pedido.</strong>
                                                      </div>
                                                   </div>
                                                   
                                          <br /><br />
                                          <div class="form-group col-sm-4">
                                                      <span>*Cantidad</span>
                                                         <div class="input-group">
                                                            <span class="input-group-btn">
                                                               <button class="btn btn-default rest-clients" type="button" >-</button>
                                                            </span> 
                                                             <input type="text" name="forfait-juvenil" class="form-control text-center count-clients" id='forfait-juvenil' readonly="readonly" required>
                                                             <span class="input-group-btn">
                                                               <button class="btn btn-default add-client" type="button">+</button>
                                                             </span>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-5">
                                                      <span>*Dias</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="JuvenilDias">
                                                               <option>Elige una opcion</option>
                                                               <option value="2">2 Dias</option>
                                                               <option value="3">3 Dias</option>
                                                               <option value="4">4 Dias</option>
                                                               <option value="5">5 Dias</option>
                                                               <option value="6">6 Dias</option>
                                                               <option value="7">7 Dias</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-3">
                                                      <span>*Carrito</span>
                                                         <button name="boton" id="botonjuv" class="btn btn-success form-control price_request" type="button">Solicitar</button>
                                                   </div>
                                                </div>
                                             
                                              Junior 
                                                <div id="Forfait1" class="row desc" style="display: none;border-left: solid;border-right: solid">
                                                    
                                          <div class="col-md-12 col-xs-12" style="margin-bottom: 20px;">
                                             <div class="col-md-3 col-xs-12">
                                                <img src="../img/fortfait/junior.jpg" class="img-responsive" style="margin-top: 30px;">
                                             </div>
                                             <div class="col-md-9 col-xs-12">
                                                <h2 class="text-center font-w300 text-black black" >Reserva <span class="font-w800">Forfait Junior</span> / Discapacitado Adulto Senior</h2>
                                                Ahora, al alquilar cualquier tu equipo puedes reservar tu Forfait Express, para que lo tengas preparado cuando entres en tu apartamento .<br /><br />

                                                El precio varía según la temporada Baja/Alta/Primavera o en Promoción, por lo que tan solo tienes que hacer la reserva y ya efectuaras el pago una vez que se pongan en contacto contigo .<br /><br />

                                                <strong>NOTA: Para reservar tu Forfait, es obligatorio enviar este formulario con  3-4 dias de antelación, de lo contrario, no se tramitará el pedido.</strong>
                                             </div>
                                          </div>
                                          <br /><br />
                                          <div class="form-group col-sm-4">
                                                      <span>*Cantidad</span>
                                                         <div class="input-group">
                                                            <span class="input-group-btn">
                                                               <button class="btn btn-default rest-clients" type="button" >-</button>
                                                            </span> 
                                                             <input type="text" name="forfait-junior" class="form-control text-center count-clients" id='forfait-junior' readonly="readonly" required>
                                                             <span class="input-group-btn">
                                                               <button class="btn btn-default add-client" type="button">+</button>
                                                             </span>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-5">
                                                      <span>*Dias</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="JuniorDias">
                                                               <option>Elige una opcion</option>
                                                               <option value="2">2 Dias</option>
                                                               <option value="3">3 Dias</option>
                                                               <option value="4">4 Dias</option>
                                                               <option value="5">5 Dias</option>
                                                               <option value="6">6 Dias</option>
                                                               <option value="7">7 Dias</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-3">
                                                      <span>*Carrito</span>
                                                         <button name="boton" id="botonjun" class="btn btn-success form-control price_request" type="button">Solicitar</button>
                                                   </div>
                                                </div>
                                             
                                              Adulto 
                                                <div id="Forfait2" class="row desc" style="display: none;border-left: solid;border-right: solid">
                                                   <div class="col-md-12 col-xs-12" style="margin-bottom: 20px;">
                                                      <div class="col-md-3 col-xs-12">
                                                         <img src="../img/fortfait/adulto.jpg" class="img-responsive" style="margin-top: 30px;">
                                                      </div>
                                                      <div class="col-md-9 col-xs-12">
                                                         <h2 class="text-center font-w300 text-black black" >Reserva <span class="font-w800">Forfait Adulto</span> </h2>
                                                         Ahora, al alquilar cualquier tu equipo puedes reservar tu Forfait Express, para que lo tengas preparado cuando entres en tu apartamento .<br /><br />

                                                El precio varía según la temporada Baja/Alta/Primavera o en Promoción, por lo que tan solo tienes que hacer la reserva y ya efectuaras el pago una vez que se pongan en contacto contigo .<br /><br />

                                                <strong>NOTA: Para reservar tu Forfait, es obligatorio enviar este formulario con  3-4 dias de antelación, de lo contrario, no se tramitará el pedido.</strong>
                                                      </div>
                                          </div>
                                          <div class="form-group col-sm-4">
                                                      <span>*Cantidad</span>
                                                         <div class="input-group">
                                                            <span class="input-group-btn">
                                                               <button class="btn btn-default rest-clients" type="button" >-</button>
                                                            </span> 
                                                             <input type="text" name="forfait-adulto" class="form-control text-center count-clients" id='forfait-adulto' readonly="readonly" required>
                                                             <span class="input-group-btn">
                                                               <button class="btn btn-default add-client" type="button">+</button>
                                                             </span>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-5">
                                                      <span>*Dias</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="AdultosDias">
                                                               <option>Elige una opcion</option>
                                                               <option value="2">2 Dias</option>
                                                               <option value="3">3 Dias</option>
                                                               <option value="4">4 Dias</option>
                                                               <option value="5">5 Dias</option>
                                                               <option value="6">6 Dias</option>
                                                               <option value="7">7 Dias</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-3">
                                                      <span>*Carrito</span>
                                                         <button name="boton" id="botonadult" class="btn btn-success form-control price_request" type="button">Solicitar</button>
                                                   </div>
                                                </div>
                                             
                                              Senior 
                                                <div id="Forfait3" class="row desc" style="display: none;border-left: solid;border-right: solid">
                                                   <div class="col-md-12 col-xs-12" style="margin-bottom: 20px;">
                                                      <div class="col-md-3 col-xs-12">
                                                         <img src="../img/fortfait/adulto.jpg" class="img-responsive" style="margin-top: 30px;">
                                                      </div>
                                                      <div class="col-md-9 col-xs-12">
                                                         <h2 class="text-center font-w300 text-black black" >Reserva <span class="font-w800">Forfait Senior</span> </h2>
                                                         Ahora, al alquilar cualquier tu equipo puedes reservar tu Forfait Express, para que lo tengas preparado cuando entres en tu apartamento .<br /><br />

                                                El precio varía según la temporada Baja/Alta/Primavera o en Promoción, por lo que tan solo tienes que hacer la reserva y ya efectuaras el pago una vez que se pongan en contacto contigo .<br /><br />

                                                <strong>NOTA: Para reservar tu Forfait, es obligatorio enviar este formulario con  3-4 dias de antelación, de lo contrario, no se tramitará el pedido.</strong>
                                                      </div>
                                          </div>
                                          <div class="form-group col-sm-4">
                                                      <span>*Cantidad</span>
                                                         <div class="input-group">
                                                            <span class="input-group-btn">
                                                               <button class="btn btn-default rest-clients" type="button" >-</button>
                                                            </span> 
                                                             <input type="text" name="forfait-senior" class="form-control text-center count-clients" id='forfait-senior' readonly="readonly" required>
                                                             <span class="input-group-btn">
                                                               <button class="btn btn-default add-client" type="button">+</button>
                                                             </span>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-5">
                                                      <span>*Dias</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="SeniorDias">
                                                               <option>Elige una opcion</option>
                                                               <option value="2">2 Dias</option>
                                                               <option value="3">3 Dias</option>
                                                               <option value="4">4 Dias</option>
                                                               <option value="5">5 Dias</option>
                                                               <option value="6">6 Dias</option>
                                                               <option value="7">7 Dias</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-3">
                                                      <span>*Carrito</span>
                                                         <button name="boton" id="botonsenior" class="btn btn-success form-control price_request" type="button">Solicitar</button>
                                                   </div>
                                                </div>
                                             
                                              Juvenil Familiar 
                                                <div id="Forfait4" class="row desc" style="display: none;border-left: solid;border-right: solid">
                                                   <div class="col-md-12 col-xs-12" style="margin-bottom: 20px;">
                                                      <div class="col-md-3 col-xs-12">
                                                         <img src="../img/fortfait/adulto.jpg" class="img-responsive" style="margin-top: 30px;">
                                                      </div>
                                                      <div class="col-md-9 col-xs-12">
                                                         <h2 class="text-center font-w300 text-black black" >Reserva <span class="font-w800">Forfait Junior Formula Familiar</span> </h2>
                                                         Ahora, al alquilar cualquier tu equipo puedes reservar tu Forfait Express, para que lo tengas preparado cuando entres en tu apartamento .<br /><br />

                                                El precio varía según la temporada Baja/Alta/Primavera o en Promoción, por lo que tan solo tienes que hacer la reserva y ya efectuaras el pago una vez que se pongan en contacto contigo .<br /><br />

                                                <strong>NOTA: Para reservar tu Forfait, es obligatorio enviar este formulario con  3-4 dias de antelación, de lo contrario, no se tramitará el pedido.</strong>
                                                      </div>
                                          </div>
                                                
                                                   <div class="form-group col-sm-4">
                                                      <span>*Cantidad</span>
                                                         <div class="input-group">
                                                            <span class="input-group-btn">
                                                               <button class="btn btn-default rest-clients" type="button" >-</button>
                                                            </span> 
                                                             <input type="text" name="forfait-juvenil-familiar" class="form-control text-center count-clients" id='forfait-juvenil-familiar' readonly="readonly" required>
                                                             <span class="input-group-btn">
                                                               <button class="btn btn-default add-client" type="button">+</button>
                                                             </span>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-5">
                                                      <span>*Dias</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="juvfaDias">
                                                               <option>Elige una opcion</option>
                                                               <option value="2">2 Dias</option>
                                                               <option value="3">3 Dias</option>
                                                               <option value="4">4 Dias</option>
                                                               <option value="5">5 Dias</option>
                                                               <option value="6">6 Dias</option>
                                                               <option value="7">7 Dias</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-3">
                                                      <span>*Carrito</span>
                                                         <button name="boton" id="botonjuvfam" class="btn btn-success form-control price_request" type="button">Solicitar</button>
                                                   </div>
                                                </div>
                                                
                                              Junio Familiar 
                                                <div id="Forfait5" class="row desc" style="display: none;border-left: solid;border-right: solid">
                                                   <div class="col-md-12 col-xs-12" style="margin-bottom: 20px;">
                                                      <div class="col-md-3 col-xs-12">
                                                         <img src="../img/fortfait/adulto.jpg" class="img-responsive" style="margin-top: 30px;">
                                                      </div>
                                                      <div class="col-md-9 col-xs-12">
                                                         <h2 class="text-center font-w300 text-black black" >Reserva <span class="font-w800">Forfait Juvenil Formula Familiar</span> </h2>
                                                         Ahora, al alquilar cualquier tu equipo puedes reservar tu Forfait Express, para que lo tengas preparado cuando entres en tu apartamento .<br /><br />

                                                El precio varía según la temporada Baja/Alta/Primavera o en Promoción, por lo que tan solo tienes que hacer la reserva y ya efectuaras el pago una vez que se pongan en contacto contigo .<br /><br />

                                                <strong>NOTA: Para reservar tu Forfait, es obligatorio enviar este formulario con  3-4 dias de antelación, de lo contrario, no se tramitará el pedido.</strong>
                                                      </div>
                                          </div>
                                                   <div class="form-group col-sm-4">
                                                      <span>*Cantidad</span>
                                                         <div class="input-group">
                                                            <span class="input-group-btn">
                                                               <button class="btn btn-default rest-clients" type="button" >-</button>
                                                            </span> 
                                                             <input type="text" name="forfait-junior-familiar" class="form-control text-center count-clients" id='forfait-junior-familiar' readonly="readonly" required>
                                                             <span class="input-group-btn">
                                                               <button class="btn btn-default add-client" type="button">+</button>
                                                             </span>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-5">
                                                      <span>*Dias</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="junfaDias">
                                                               <option>Elige una opcion</option>
                                                               <option value="2">2 Dias</option>
                                                               <option value="3">3 Dias</option>
                                                               <option value="4">4 Dias</option>
                                                               <option value="5">5 Dias</option>
                                                               <option value="6">6 Dias</option>
                                                               <option value="7">7 Dias</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-3">
                                                      <span>*Carrito</span>
                                                         <button name="boton" id="botonjunfam" class="btn btn-success form-control price_request" type="button">Solicitar</button>
                                                   </div>
                                                </div>
                                         </div>-->
                                    
                                      <!-- Alquiler de Material -->
                                       <div class="heading-block fancy-title nobottomborder title-bottom-border col-xs-12">
                                          <h2 class="black font-w800 center t400 ls1 push-20 " style="color:white;font-size:23px">
                                             Alquiler de material
                                          </h2>
                                       </div>

                                    <!-- Selector material -->
                                       <div class="col-sm-2 col-xs-12  left">
                                            
                                            <div class="form-group">
                                                <input type="radio" name="material" value="0">Packs clases<br />
                                                <input type="radio" name="material" value="1">Esqui<br />
                                                <input type="radio" name="material" value="2">Snowboard<br />
                                                <input type="radio" name="material" value="3">Snowblade<br />
                                                <input type="radio" name="material" value="4">Cascos<br />
                                             </div>
                                         </div>

                                       <div class="form-group col-sm-10">
                                          
                                            <!-- Packs -->
                                               <div id="material0" class="row desc" style="display: none;border-left: solid;border-right: solid">
                                                    <span><h2 align="center">Reservas de Packs</h2></span>
                                          
                                          <table class="table table-bordered table-inverse table-responsive" style="width: 98%;margin-left: 5px">
                                             <th style="width: 15%">Nombre</th>
                                             <th>Tipo</th>
                                             <th>Equipo</th>
                                             <th>Clases</th>
                                             <tr>
                                                <td>1 Pax</td>
                                                <td>Esquí</td>
                                                <td>
                                                   Esquís gama MEDIUM. Botas gama MEDIUM. Bastones Incluidos.                    
                                                </td>
                                                <td>
                                                   3 Clases Colectivas. Duración 2h/día.
                                                </td>
                                             </tr>
                                             <tr>
                                                <td>2 Pax</td>
                                                <td>Esquí</td>
                                                <td>
                                                   Esquís gama MEDIUM. Botas gama MEDIUM. Bastones Incluidos.
                                                </td>
                                                <td>
                                                   2 Clases Colectivas. Duración 2h/día.
                                                </td>
                                             </tr>
                                             <tr>
                                                <td>1 Pax</td>
                                                <td>Snow</td>
                                                <td>
                                                   Snowboard gama MEDIUM. Botas gama MEDIUM.
                                                </td>
                                                <td>
                                                   2 Clases Colectivas .Duración 2h/día.
                                                </td>
                                             </tr>
                                             <tr>
                                                <td>2 Pax</td>
                                                <td>Snow</td>
                                                <td>                                                  
                                                      Snowboard gama MEDIUM. Botas gama MEDIUM.
                                                </td>
                                                <td>                                                  
                                                      2 Clases Colectivas. Duración 2h/día.
                                                </td>
                                             </tr>
                                          </table>
                                          <br />
                                          <div class="form-group col-sm-3">
                                                      <span>*Cantidad</span>
                                                         <div class="input-group">
                                                            <span class="input-group-btn">
                                                               <button class="btn btn-default rest-clients" type="button" >-</button>
                                                            </span> 
                                                             <input type="text" name="material-pack-cant" id="material-pack-cant" class="form-control text-center count-clients" id='material-pack-cant' readonly="readonly" required>
                                                             <span class="input-group-btn">
                                                               <button class="btn btn-default add-client" type="button">+</button>
                                                             </span>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-4 col-xs-12">
                                                      <span>*Tipo</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="packtipo">
                                                               <option>Tipo</option>
                                                               <option value="Esqui 1">Esqui 1</option>
                                                               <option value="Esqui 2">Esqui 2</option>
                                                               <option value="Snow 1">Snow 1</option>
                                                               <option value="Snow 2">Snow 2</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-2 col-xs-12">
                                                      <span>*Dias</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="packdias">
                                                               <option>Dias</option>
                                                               <option value="2">2 Dias</option>
                                                               <option value="3">3 Dias</option>
                                                               <option value="4">4 Dias</option>
                                                               <option value="5">5 Dias</option>
                                                               <option value="6">6 Dias</option>
                                                               <option value="7">7 Dias</option>
                                                            </select>
                                                         </div>
                                                   </div>
                                                   <div class="form-group col-sm-2">
                                                      <span>*Carrito</span>
                                                         <button name="boton" id="botonpack" class="btn btn-success form-control price_request" type="button">Solicitar</button>
                                                   </div>
                                                </div>

                                             <!-- Esquis -->
                                                <div id="material1" class="row desc" style="display: none;border-left: solid;border-right: solid">
                                                    <span><h2 align="center">Reservas de Esquís</h2></span>
                                                      
                                                    <table class="table table-bordered table-inverse" style="width: 98%;margin-left: 5px">
                                             <th style="width: 25%">Nombre</th>
                                             <th>Tipo</th>
                                             <th>Equipo</th>
                                             <tr>
                                                <td>Pack </td>
                                                <td>Adulto</td>
                                                <td>Esquis, Botas, Bastones</td>
                                             </tr>
                                             <tr>
                                                <td>Pack </td>
                                                <td>Niño</td>
                                                <td>Esquis, Botas, Bastones</td>
                                             </tr>
                                             <tr>
                                                <td>Esquís + bastones </td>
                                                <td>Adulto</td>
                                                <td>Esquis, Bastones</td>
                                             </tr>
                                             <tr>
                                                <td>Esquís + bastones </td>
                                                <td>Niño</td>
                                                <td>Esquis, Bastones</td>
                                             </tr>
                                             <tr>
                                                <td>Botas </td>
                                                <td>Adulto</td>
                                                <td>Botas</td>
                                             </tr>
                                             <tr>
                                                <td>Botas </td>
                                                <td>Niño</td>
                                                <td>Botas</td>
                                             </tr>
                                          </table>
                                          
                                          <div class="form-group col-sm-3">
                                                      <span>*Cantidad</span>
                                                         <div class="input-group">
                                                            <span class="input-group-btn">
                                                               <button class="btn btn-default rest-clients" type="button" >-</button>
                                                            </span> 
                                                             <input type="text" name="material-Esquis-cant" class="form-control text-center count-clients" id='material-Esquis-cant' readonly="readonly" required>
                                                             <span class="input-group-btn">
                                                               <button class="btn btn-default add-client" type="button">+</button>
                                                             </span>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-3">
                                                      <span>*Dias</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="EsquisDias">
                                                               <option>Dias</option>
                                                               <option value="2">2 Dias</option>
                                                               <option value="3">3 Dias</option>
                                                               <option value="4">4 Dias</option>
                                                               <option value="5">5 Dias</option>
                                                               <option value="6">6 Dias</option>
                                                               <option value="7">7 Dias</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-2 col-xs-12">
                                                      <span>*Tipo</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="esquitipo">
                                                               <option>Tipo</option>
                                                               <option value=" Pack adulto esqui ">Pack adulto</option>
                                                               <option value=" Pack niño esqui ">Pack niño</option>
                                                               <option value=" Esquis + B Ad ">Esquis + B Ad</option>
                                                               <option value=" Esquis + B Ni ">Esquis + B Ni</option>
                                                               <option value=" Botas adulto esqui ">Botas adulto</option>
                                                               <option value=" Botas Niño esqui ">Botas Niño</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-2">
                                                      <span>*Gama</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="esquisgama">
                                                               <option>Gama</option>
                                                               <option value="Medium">Medium</option>
                                                               <option value="Alta">Alta</option>
                                                            </select>
                                                         </div>
                                                   </div>

                                                   <div class="form-group col-sm-2">
                                                      <span>*Carrito</span>
                                                         <button name="boton" id="botonesquis" class="btn btn-success form-control price_request" type="button">Solicitar</button>
                                                   </div>
                                                </div>                                        

                                             <!-- Snowboard -->
                                                <div id="material2" class="row desc" style="display: none;border-left: solid;border-right: solid">
                                                    <span><h2 align="center">Reservas de Snowboard</h2></span>
                                                      
                                                    <table class="table table-bordered table-inverse" style="width: 98%;margin-left: 5px">
                                             <th style="width: 25%">Nombre</th>
                                             <th>Tipo</th>
                                             <th>Equipo</th>
                                             <tr>
                                                <td>Pack </td>
                                                <td>Adulto</td>
                                                <td>Tabla de Snowboard , Botas</td>
                                             </tr>
                                             <tr>
                                                <td>Pack </td>
                                                <td>Niño</td>
                                                <td>Tabla de Snowboard , Botas</td>
                                             </tr>
                                             <tr>
                                                <td>Tabla Snowboard</td>
                                                <td>Adulto</td>
                                                <td>Tabla Snowboard</td>
                                             </tr>
                                             <tr>
                                                <td>Tabla Snowboard</td>
                                                <td>Niño</td>
                                                <td>Tabla Snowboard</td>
                                             </tr>
                                             <tr>
                                                <td>Botas </td>
                                                <td>Adulto</td>
                                                <td>Botas</td>
                                             </tr>
                                             <tr>
                                                <td>Botas </td>
                                                <td>Niño</td>
                                                <td>Botas</td>
                                             </tr>
                                          </table>                                  

                                                   <div class="form-group col-sm-3">
                                                      <span>*Cantidad</span>
                                                         <div class="input-group">
                                                            <span class="input-group-btn">
                                                               <button class="btn btn-default rest-clients" type="button" >-</button>
                                                            </span> 
                                                             <input type="text" name="material-snow-cant" class="form-control text-center count-clients" id='material-snow-cant' readonly="readonly" required>
                                                             <span class="input-group-btn">
                                                               <button class="btn btn-default add-client" type="button">+</button>
                                                             </span>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-3">
                                                      <span>*Dias</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="snowDias">
                                                               <option>Dias</option>
                                                               <option value="2">2 Dias</option>
                                                               <option value="3">3 Dias</option>
                                                               <option value="4">4 Dias</option>
                                                               <option value="5">5 Dias</option>
                                                               <option value="6">6 Dias</option>
                                                               <option value="7">7 Dias</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-2 col-xs-12">
                                                      <span>*Tipo</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="snowtipo">
                                                               <option>Tipo</option>
                                                               <option value=" Pack adulto snow ">Pack adulto</option>
                                                               <option value=" Pack niño snow ">Pack niño</option>
                                                               <option value=" Tabla Snow Ad ">Tabla Snow Ad</option>
                                                               <option value=" Tabla Snow Ni ">Tabla Snow Ni</option>
                                                               <option value=" Botas adulto snow ">Botas adulto</option>
                                                               <option value=" Botas Niño snow ">Botas Niño</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-2">
                                                      <span>*Gama</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="snowgama">
                                                               <option>Gama</option>
                                                               <option value="Medium">Medium</option>
                                                               <option value="Alta">Alta</option>
                                                            </select>
                                                         </div>
                                                   </div>
                                                   
                                                   <div class="form-group col-sm-2">
                                                      <span>*Carrito</span>
                                                         <button name="boton" id="botonsnow" class="btn btn-success form-control price_request" type="button">Solicitar</button>
                                                   </div>
                                                </div>      

                                             <!-- Snowblade -->
                                                <div id="material3" class="row desc" style="display: none;border-left: solid;border-right: solid">
                                                    <span><h2 align="center">Reservas de Snowblade</h2></span>
                                                      
                                                    <table class="table table-bordered table-inverse" style="width: 98%;margin-left: 5px">
                                             <th style="width: 25%">Nombre</th>
                                             <th>Equipo</th>
                                             <tr>
                                                <td>Pack </td>
                                                <td>Tabla de Snowblade , Botas</td>
                                             </tr>
                                             <tr>
                                                <td>Snowblade</td>
                                                <td>Tabla Snowblade</td>
                                             </tr>
                                          </table>                                  

                                                   <div class="form-group col-sm-3">
                                                      <span>*Cantidad</span>
                                                         <div class="input-group">
                                                            <span class="input-group-btn">
                                                               <button class="btn btn-default rest-clients" type="button" >-</button>
                                                            </span> 
                                                             <input type="text" name="material-blade-cant" class="form-control text-center count-clients" id='material-blade-cant' readonly="readonly" required>
                                                             <span class="input-group-btn">
                                                               <button class="btn btn-default add-client" type="button">+</button>
                                                             </span>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-3">
                                                      <span>*Dias</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="bladeDias">
                                                               <option>Dias</option>
                                                               <option value="2">2 Dias</option>
                                                               <option value="3">3 Dias</option>
                                                               <option value="4">4 Dias</option>
                                                               <option value="5">5 Dias</option>
                                                               <option value="6">6 Dias</option>
                                                               <option value="7">7 Dias</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-2 col-xs-12">
                                                      <span>*Tipo</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="bladeTipo">
                                                               <option>Tipo</option>
                                                               <option value=" Pack completo Snowblade ">Pack Snowblade</option>
                                                               <option value=" Tabla Snowblade ">Tabla Snowblade</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   
                                                   <div class="form-group col-sm-2">
                                                      <span>*Carrito</span>
                                                         <button name="boton" id="botonblade" class="btn btn-success form-control price_request" type="button">Solicitar</button>
                                                   </div>
                                                </div>      

                                             <!-- Cascos -->
                                                <div id="material4" class="row desc" style="display: none;border-left: solid;border-right: solid">
                                                    <span><h2 align="center">Reservas de Cascos</h2></span>
                                                      
                                                    <table class="table table-bordered table-inverse" style="width: 98%;margin-left: 5px">
                                             <th style="width: 25%">Nombre</th>
                                             <th>Equipo</th>
                                             <tr>
                                                <td>Casco Adulto </td>
                                                <td>Casco para Adulto</td>
                                             </tr>
                                             <tr>
                                                <td>Casco Niño</td>
                                                <td>Casco para Niño</td>
                                             </tr>
                                          </table>                                  

                                                   <div class="form-group col-sm-3">
                                                      <span>*Cantidad</span>
                                                         <div class="input-group">
                                                            <span class="input-group-btn">
                                                               <button class="btn btn-default rest-clients" type="button" >-</button>
                                                            </span> 
                                                             <input type="text" name="material-casco-cant" class="form-control text-center count-clients" id='material-casco-cant' readonly="readonly" required>
                                                             <span class="input-group-btn">
                                                               <button class="btn btn-default add-client" type="button">+</button>
                                                             </span>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-3 col-xs-12"">
                                                      <span>*Dias</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="cascoDias">
                                                               <option>Dias</option>
                                                               <option value="2">2 Dias</option>
                                                               <option value="3">3 Dias</option>
                                                               <option value="4">4 Dias</option>
                                                               <option value="5">5 Dias</option>
                                                               <option value="6">6 Dias</option>
                                                               <option value="7">7 Dias</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   <div class="form-group col-sm-2 col-xs-12" >
                                                      <span>*Tipo</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="cascoTipo">
                                                               <option>Tipo</option>
                                                               <option value="Adulto">Adulto</option>
                                                               <option value="Niño">Niño</option>
                                                            </select>

                                                         </div>

                                                   </div>
                                                   
                                                   <div class="form-group col-sm-2 col-xs-12">
                                                      <span>*Carrito</span>
                                                         <button name="boton" id="botoncasco" class="btn btn-success form-control price_request" type="button">Solicitar</button>
                                                   </div>
                                                </div>      
                                       </div>

                                      <!-- Clases -->

                                       <div class="heading-block fancy-title nobottomborder title-bottom-border col-xs-12">
                                          <h2 class="black font-w800 center t400 ls1 push-20 " style="color:white;font-size:23px">
                                             Clases/sursillo esquí
                                          </h2>
                                       </div>

                                     <!-- Selector de clases -->

                                         <div class="col-sm-12 col-xs-12 push-30">
                                            
                                    <div class="col-md-2 col-xs-6 clase" data-value="0">
                                             <div class="col-xs-12 not-padding">
                                                <img src="../img/fortfait/clase-particular.jpg" class="img-responsive push-10"/>
                                                <h3 class="text-center title-forfait push-5">
                                                   Clases particulares
                                                </h3>
                                             </div>
                                            </div>
                                            <div class="col-md-2 col-xs-6 clase" data-value="1">
                                             <div class="col-xs-12 not-padding">
                                                <img src="../img/fortfait/clase-colectiva-semanal.jpg" class="img-responsive push-10"/>
                                                <h3 class="text-center title-forfait push-5">
                                                   Clases colectivas semanal
                                                </h3>
                                             </div>
                                            </div>

                                         </div>
                                          

                                       <div class="form-group col-sm-12 col-xs-12">
                                          <!-- Particulares -->
                                          <div id="clase0" class="row desc" style="display: none;border-left: solid;border-right: solid">       
                                             <div class="col-xs-12 push-12">
                                                <div class="col-md-3">
                                                   <img src="../img/fortfait/clase-particular.jpg" class="img-responsive push-10" style="margin-top: 30px;" />
                                                </div>
                                                <div class="col-md-9">
                                                   <h2 class="text-center font-w300 text-black black" >CLASES <span class="font-w800">Particulares</span> </h2>
                                                   Son clases personalizadas, se recomienda que mínimo contraten a partir de 2 horas de duración, para que sea mayor el aprovechamiento de las mismas. Se imparten de 1 a 4 personas, del mismo nivel y estilo (esquí o snow), la agrupación de los alumnos es por cuenta del cliente.<br /><br />

                                                   Para niños de 3 y 4 años, debe ir 1 niño por profesor. No se recomienda que den clases juntos adultos y niños, ya que el sistema de enseñanza es diferente y el profesor tiene que estar pendiente de los niños por los que los adultos no aprovecharían bien la clase.<br /><br />
                                                </div>
                                             </div>                     

                                       <!-- nº Personas -->
                                          <div class="form-group col-sm-4">
                                                      <span>*Personas</span>
                                                     <div class="input-group" >
                                                      <select type="text" class="form-control clase-particular-cant" id="clase-particular-cant">
                                                         <option>seleccione alumnos</option>
                                                         <option value="1" data-price="47.00">1</option>
                                                         <option value="2" data-price="51.00">2</option>
                                                         <option value="3" data-price="56.00">3</option>
                                                         <option value="4" data-price="60.00">4</option>
                                                      </select>
                                                     </div>
                                                   </div>
                                                   
                                                <!-- Hora de inicio -->
                                                   <div class="form-group col-sm-4">
                                                      <span>Hora</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="clasehora">
                                                               <option>Hora de inicio</option>
                                                               <option value="10:00">10:00</option>
                                                               <option value="12:00">12:00</option>
                                                               <option value="13:00">13:00</option>
                                                               <option value="14:00">14:00</option>
                                                               <option value="15:00">15:00</option>
                                                               <option value="16:00">16:00</option>
                                                            </select>
                                                         </div>
                                                   </div>

                                                <!-- nº Horas -->
                                                   <div class="form-group col-sm-4">
                                                      <span>Nª de horas</span>
                                                      <div class="input-group" >
                                                         <select type="text" class="form-control" id="clasehoras">
                                                            <option>Nº de horas</option>
                                                            <option value="1">1 Horas</option>
                                                            <option value="2">2 Horas</option>
                                                            <option value="3">3 Horas</option>
                                                            <option value="4">4 Horas</option>
                                                            <option value="5">5 Horas</option>
                                                            <option value="6">6 Horas</option>
                                                         </select>
                                                      </div>
                                                   </div>

                                                <!-- nº Horas -->
                                                   <div class="form-group col-sm-4">
                                                      <span>*Modalidad</span>
                                                      <div class="input-group" >
                                                         <select type="text" class="form-control" id="clasetipo">
                                                            <option>Seleccione clase</option>
                                                            <option value="Esquí niños principiantes">Esquí niños principiantes</option>
                                                            <option value="Esquí niños avanzados">Esquí niños avanzados</option>
                                                            <option value="Esquí">Esquí</option>
                                                            <option value="Snow">Snow</option>
                                                            <option value="Ciegos">Ciegos</option>
                                                         </select>
                                                      </div>
                                                   </div>

                                                <!-- Profesor -->
                                                   
                                                   <input type="hidden" id="claseprofesor" value="---">
                                                   
                                                   
                                                <!-- Idioma -->
                                                   <div class="form-group col-sm-4">
                                                      <span>*Idioma</span>
                                                         <div class="input-group" >
                                                            <select type="text" class="form-control" id="claseidioma">
                                                               <option>Idioma</option>
                                                               <option value="Español">Español</option>
                                                               <option value="Ingles">Ingles</option>
                                                               <option value="Portugues">Portugues</option>
                                                               <option value="Frances">Frances</option>
                                                               <option value="Aleman">Aleman</option>
                                                               <option value="Ruso">Ruso</option>
                                                            </select>
                                                         </div>
                                                   </div>


                                                   <div class="form-group col-sm-4" >
                                                      <button name="boton" id="botonparticular" class="btn btn-success form-control price_request" type="button">Solicitar</button>
                                                   </div>
                                             </div>

                                            <!-- colectivo -->
                                               <div id="clase1" class="row desc" style="display: none;border-left: solid;border-right: solid">                 <span><h2 align="center">Reserva Clase Colectivas</h2></span>
                                          
                                          <table class="table table-bordered table-inverse table-responsive" style="width: 98%;margin-left: 5px">
                                             <th>Tipo</th>
                                             <th>Modalildad</th>
                                             <th>Horario</th>
                                             <tr>
                                                <td>Fin de semana</td>
                                                <td>Esquí</td>
                                                <td>S 12:00-15:00<br>
                                                   D 10:00-13:00</td>
                                             </tr>
                                             <tr>
                                                <td>Fin de semana</td>
                                                <td>Snow</td>
                                                <td>S 12:00-14:00<br>
                                                   D 10:00-12:00</td>
                                             </tr>
                                             <tr>
                                                <td>Semanal</td>
                                                <td>Esquí</td>
                                                <td>L-V 10:00-13:00</td>
                                             </tr>
                                             <tr>
                                                <td>Fin de semana</td>
                                                <td>Snow</td>
                                                <td>L-V 10:00-12:00</td>
                                             </tr>
                                          </table>                           
                                          <p>*Los cursos son para un mínimo de 4 personas y máximo de 8</p>
                                          
                                          <!-- Dias -->
                                                      <div class="form-group col-sm-4">
                                                         <span>*Dias</span>
                                                            <div class="input-group" >
                                                               <select type="text" class="form-control" id="colecDias">
                                                                  <option>Dias</option>
                                                                  <option value="2 Dias" data-week="weekend">2 Dias</option>
                                                                  <option value="3 Dias" data-week="week">3 Dias</option>
                                                                  <option value="4 Dias" data-week="week">4 Dias</option>
                                                                  <option value="5 Dias" data-week="week">5 Dias</option>
                                                               </select>
                                                            </div>
                                                      </div>

                                          <!-- nº Personas -->
                                             <div class="form-group col-sm-4">
                                                         <span>*Personas</span>
                                                            <div class="input-group">
                                                               <span class="input-group-btn">
                                                                  <button class="btn btn-default rest-clients" type="button" >-</button>
                                                               </span> 
                                                                <input type="text" name="clase-colectivo-cant" class="form-control text-center count-clients" id='clase-colectivo-cant' readonly="readonly" required>
                                                                <span class="input-group-btn">
                                                                  <button class="btn btn-default add-client" type="button">+</button>
                                                                </span>
                                                            </div>
                                                      </div>                                               

                                                   <!-- Modalidad -->
                                                      <div class="form-group col-sm-4">
                                                         <span>*Modalidad</span>
                                                            <div class="input-group" >
                                                               <select type="text" class="form-control" id="colectipo">
                                                                  <option>Clase</option>
                                                                  <option value="Esqui Fin de semana" data-week="weekend">Esqui Fin de semana</option>
                                                                  <option value="Snow Fin de semana" data-week="weekend">Snow Fin de semana</option>
                                                                  <option value="Esquí Semanal" data-week="week">Esquí Semanal</option>
                                                                  <option value="Snow Semanal" data-week="week">Snow Semanal</option>
                                                               </select>
                                                            </div>
                                                      </div>

                                                   <!-- Profesor -->
                                                      <div class="form-group col-sm-4">
                                                         <span>*Profesor</span>
                                                            <div class="input-group" >
                                                               <select type="text" class="form-control" id="colecprofesor">
                                                                  <option>Profesor</option>
                                                                  <option value="Hombre">Hombre</option>
                                                                  <option value="Mujer">Mujer</option>
                                                               </select>
                                                            </div>
                                                      </div>
                                                      
                                                   <!-- Idioma -->
                                                      <div class="form-group col-sm-4">
                                                         <span>*Idioma</span>
                                                            <div class="input-group" >
                                                               <select type="text" class="form-control" id="colecidioma">
                                                                  <option>Idioma</option>
                                                                  <option value="Español">Español</option>
                                                                  <option value="Ingles">Ingles</option>
                                                                  <option value="Portugues">Portugues</option>
                                                                  <option value="Frances">Frances</option>
                                                                  <option value="Aleman">Aleman</option>
                                                                  <option value="Ruso">Ruso</option>
                                                               </select>
                                                            </div>
                                                      </div>


                                                   <div class="form-group col-sm-3 col-sm-offset-4" >
                                                      <span>*Carrito</span>
                                                         <button name="boton" id="botoncolectivo" class="btn btn-success form-control price_request" type="button">Solicitar</button>
                                                   </div>
                                                </div>
                                       </div>                                                                 

                                  </div>
                                  <div style="text-align: right; margin-right: 5px"><h3 style="color: black">Atencion al cliente: 958-48-01-68</h3></div>
                              </div>

                      </div>
                  </div>
                  <div class="col-md-10 col-lg-3  sin-margin-mobile" style="padding:0!important;">
                      <div id='calendar-content' class='container-fluid'>                        
                            <div class="panel panel-default" style="background-color: rgba(255,255,255,0.75);">
                                <div class="panel-body">                                                                      
                                    <!-- Carrito -->
                                      <div class="form-group col-sm-12"  id="content-carrito">
                                          <h3 class="form-group text-center black font-w300" style="text-transform: uppercase;">Resumen Solicitud</h3>
                                          <p class="carrito_forfaits"></p>
                                          <p class="carrito"></p>
                                      </div>
                                    <div class="heading-block fancy-title nobottomborder title-bottom-border col-xs-12" style="margin-bottom:0 !important;">
                                        <h2 class="black font-w800 center t400 ls1 push-20 " style="color:white;font-size:23px; margin-bottom:0 !important;"></h2>
                                    </div>
                                    <div id="cc" class="form-group col-sm-12 text-center">
                                        <div id="cc" class="form-group col-sm-12 text-center h4" style="margin-bottom:0;">Datos de Pago</div>
                                        <div id="cc" class="form-group col-sm-12 text-center h5" style="margin-bottom:8px;">Tarjeta Débito/Crédito</div>
                                        <input type="text" min="16" max="16" name="cc_pan" placeholder="Número Tarjeta"/>
                                        <input type="text" name="cc_name" placeholder="Nombre completo"/>
                                        <input type="text" name="cc_expiry" placeholder="Expiración MM/YY"/>
                                        <input type="text" min="3" max="3" name="cc_cvc" placeholder="CVC"/>
                                    </div>
                                      <div class="form-group col-xs-12 text-center" style="margin-top: 20px;">
                                          <button type="button" class="btn btn-primary btn-lg text-center" id="confirm-reserva">Solicitar reserva</button>
                                      </div>
                                </div>
                            </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>
      </form>
    </div>

    <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <span style="font-weight: bold;">Debe seleccionar Fecha de Forfaits</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../pages/js/bootstrap-notify.js"></script>
    <script type="text/javascript" src="../frontend/js/plugins.js"></script>
    <script type="text/javascript" src="../frontend/js/functions.js"></script>   

    <script>
        jQuery(document).ready(function(){
            var clients = 0;
            var maxClients  = 10;
            var minClients  = 0;
            $('.count-clients').val(clients);
         //añadir clientes
         $(".add-client").click(function(){
            var actualClients = $(this).parent().parent().children('.count-clients').val();
            if(actualClients < maxClients)
            {
               actualClients = parseInt(actualClients) + 1;
               $(this).parent().parent().children('.count-clients').val(actualClients);
            } else 
               swal("Oops!", "Solo permitimos un maximo de "+ maxClients +"personas por reserva", "info");  
         });

         //restar clientes
         $(".rest-clients").click(function(){
            var actualClients = $(this).parent().parent().children('.count-clients').val();
            if(parseInt(actualClients) > 0)
            {
               actualClients = parseInt(actualClients) - 1;
               $(this).parent().parent().children('.count-clients').val(actualClients);
            } 
         });
         });
      
    </script>
    <script type="text/javascript">
        
        if($('div.mobile:visible').length == 0){
            $('div.mobile').remove();
        }
        
        if($('div.desktop:visible').length == 0){
            $('div.desktop').remove();
        }

        $("#myModal button.btn").on("click", function (e) {
//            console.log('test');
            $("#myModal").modal('hide'); // dismiss the dialog
        });

        $("#myModal").on("hide", function () { // remove the event listeners when the dialog is dismissed
            $("#myModal a.btn").off("click");
        });

        $("#myModal").on("hidden", function () { // remove the actual elements from the DOM when fully hidden
            $("#myModal").remove();
        });
        
        $('select#colectipo').on('change',function(){
            week_type = $(this).find('option:selected').attr('data-week');
            
            if(week_type === 'weekend'){
                $('select#colecDias').prop('selectedIndex',0);
                $('select#colecDias option[data-week="week"]').hide();
                $('select#colecDias option[data-week="weekend"]').show();
            }else{
                $('select#colecDias option[data-week="weekend"]').hide();
                $('select#colecDias option[data-week="week"]').show();
            }
        });

        function checkForfaitDates(){
            if(start_date = $('div#requests_header input[name="date-entrada"]').val().length > 0 && $('div#requests_header input[name="date-salida"]').val().length > 0){
                return true;
            }else{
//                console.log(false);
                $("#myModal").modal({ // wire up the actual modal functionality and show the dialog
                    "backdrop": "static",
                    "keyboard": true,
                    "show": true // ensure the modal is shown immediately
                });

                return false;
            }
        }
        
        function requestPrice(cont,type,subtype,quantity,times,ski_type = null,material_type = null){
            
            start_date = $('div#requests_header input[name="date-entrada"]').val();
            end_date = $('div#requests_header input[name="date-salida"]').val();
            
//            console.log(cont);
//            console.log(start_date);
//            console.log(end_date);
//            console.log(type);
//            console.log(subtype);
//            console.log(quantity);
//            console.log(times);
//            console.log(start_date);
//            console.log(end_date);
//            console.log(ski_type);
//            console.log(material_type);

            $.ajax({
//                headers: {
//                    'X-CSRF-TOKEN': 
//                },
                type: "POST",
                url: "/ajax/requestPrice",
                data: {start_date:start_date,end_date:end_date,type:type,subtype:subtype,quantity:quantity,times:times,ski_type:ski_type,material_type:material_type},
                dataType:'json',
//                async: false,
                success: function(response){
                    price = JSON.stringify(response).replace('.',',');
//                    console.log(price);
                    $("#" + cont).append(" - "+price+"&euro;");
//                    console.log($('input[name="carrito['+cont+']"]').val());
                    input_cont = $('input[name="carrito['+cont+']"]');
                    $('input[name="carrito['+cont+']"]').val(input_cont.val()+" - "+price+"&euro;");
                    $("#" + cont).append("<input type='hidden' name='prices["+cont+"]' value='"+response+"'>");
                },
                error: function(response){
//                    console.log(response);
                }
            });

        }

        if (typeof wabtn4fg === "undefined") {
            wabtn4fg = 1;
            h = document.head || document.getElementsByTagName("head")[0], s = document.createElement("script");
            s.type = "text/javascript";
            s.src = "js/button.js";
            h.appendChild(s);
        }

        var cont = 0;
        // Forfaits
        $(document).ready(function () {
            // $("input[name$='forfait']").click(function() {
            $(".forfait").click(function () {
                var test = $(this).attr('data-value');
                $("div.desc").hide();
                $("#Forfait" + test).show();

                $("div.forfait").each(function (index, el) {
                    $(this).css('border', 'none');
                });

                $(this).css('border', '3px solid #00b4e9');
            });


            // Funcion de creacion de carrito
            $("#botonjuv").click(function () {

                if(checkForfaitDates() === true){
                    if ($(".carrito").text() == "") {
                        if ($("#forfait-juvenil").val() != 0 && $("#JuvenilDias option:selected").html() != "Elige una opcion") {

                            $(".carrito").html("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#forfait-juvenil").val() + " Forfait Juvenil ");
                            $("#" + cont).append($("#JuvenilDias").val() + " Dias" + "");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='forfaits[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            
                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'forfait','juv',$("#forfait-juvenil").val(),$("#JuvenilDias").val());

                        } else {
                            alert("Debes ingresar una cantidad y un bono");
                        }

                    } else {
                        if ($("#forfait-juvenil").val() != 0 && $("#JuvenilDias option:selected").html() != "Elige una opcion") {

                            $(".carrito").append("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#forfait-juvenil").val() + " Forfait Juvenil ");
                            $("#" + cont).append($("#JuvenilDias").val() + " Dias" + "");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='forfaits[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            
                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'forfait','juv',$("#forfait-juvenil").val(),$("#JuvenilDias").val());

                        } else {
                            alert("Debes ingresar una cantidad y un bono");
                        }
                    }
                    cont++;
                }

            });

            $("#botonjun").click(function () {

                if(checkForfaitDates() === true){
                    if ($(".carrito").text() == "") {
                        if ($("#forfait-junior").val() != 0 && $("#JuniorDias option:selected").html() != "Elige una opcion") {
                            $(".carrito").html("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#forfait-junior").val() + " Forfait Junior ");
                            $("#" + cont).append($("#JuniorDias").val() + " Dias" + "");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='forfaits[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'forfait','jun',$("#forfait-junior").val(),$("#JuniorDias").val());

                        } else {
                            alert("Debes ingresar una cantidad y un bono");
                        }

                    } else {
                        if ($("#forfait-junior").val() != 0 && $("#JuniorDias option:selected").html() != "Elige una opcion") {
                            $(".carrito").append("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#forfait-junior").val() + " Forfait Junior ");
                            $("#" + cont).append($("#JuniorDias").val() + " Dias" + "");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='forfaits[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'forfait','jun',$("#forfait-junior").val(),$("#JuniorDias").val());
                        } else {
                            alert("Debes ingresar una cantidad y un bono");
                        }
                    }
                    cont++;
                }
            });

            $("#botonadult").click(function () {

                if(checkForfaitDates() === true){
                    if ($(".carrito").text() == "") {
                        if ($("#forfait-adulto").val() != 0 && $("#AdultosDias option:selected").html() != "Elige una opcion") {
                            $(".carrito").html("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#forfait-adulto").val() + " Forfait Adultos ");
                            $("#" + cont).append($("#AdultosDias").val() + " Dias" + "");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='forfaits[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'forfait','adult',$("#forfait-adulto").val(),$("#AdultosDias").val());
                        } else {
                            alert("Debes ingresar una cantidad y un bono");
                        }

                    } else {
                        if ($("#forfait-adulto").val() != 0 && $("#AdultosDias option:selected").html() != "Elige una opcion") {
                            $(".carrito").append("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#forfait-adulto").val() + " Forfait Adultos ");
                            $("#" + cont).append($("#AdultosDias").val() + " Dias" + "");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='forfaits[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'forfait','adult',$("#forfait-adulto").val(),$("#AdultosDias").val());
                        } else {
                            alert("Debes ingresar una cantidad y un bono");
                        }
                    }
                    cont++;
                }
                
            });

            $("#botonsenior").click(function () {

                if(checkForfaitDates() === true){
                    if ($(".carrito").text() == "") {
                        if ($("#forfait-senior").val() != 0 && $("#SeniorDias option:selected").html() != "Elige una opcion") {
                            $(".carrito").html("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#forfait-senior").val() + " Forfait Senior ");
                            $("#" + cont).append($("#SeniorDias").val() + " Dias" + "");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='forfaits[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'forfait','senior',$("#forfait-senior").val(),$("#SeniorDias").val());
                        } else {
                            alert("Debes ingresar una cantidad y un bono");
                        }

                    } else {
                        if ($("#forfait-senior").val() != 0 && $("#SeniorDias option:selected").html() != "Elige una opcion") {
                            $(".carrito").append("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#forfait-senior").val() + " Forfait Senior ");
                            $("#" + cont).append($("#SeniorDias").val() + " Dias" + "");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='forfaits[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'forfait','senior',$("#forfait-senior").val(),$("#SeniorDias").val());
                        } else {
                            alert("Debes ingresar una cantidad y un bono");
                        }
                    }
                    cont++;
                }
                
            });

            $("#botonjuvfam").click(function () {
            
                if(checkForfaitDates() === true){
                    if ($(".carrito").text() == "") {
                        if ($("#forfait-juvenil-familiar").val() != 0 && $("#juvfaDias option:selected").html() != "Elige una opcion") {
                            $(".carrito").html("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#forfait-juvenil-familiar").val() + " Forfait juvenil-Familiar ");
                            $("#" + cont).append($("#juvfaDias").val() + " Dias" + "");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='forfaits[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'forfait','juvfam',$("#forfait-juvenil-familiar").val(),$("#juvfaDias").val());
                        } else {
                            alert("Debes ingresar una cantidad y un bono");
                        }

                    } else {
                        if ($("#forfait-juvenil-familiar").val() != 0 && $("#juvfaDias option:selected").html() != "Elige una opcion") {
                            $(".carrito").append("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#forfait-juvenil-familiar").val() + " Forfait juvenil-Familiar ");
                            $("#" + cont).append($("#juvfaDias").val() + " Dias" + "");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='forfaits[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'forfait','juvfam',$("#forfait-juvenil-familiar").val(),$("#juvfaDias").val());
                        } else {
                            alert("Debes ingresar una cantidad y un bono");
                        }
                    }
                    cont++;
                }
                
            });

            $("#botonjunfam").click(function () {

                if(checkForfaitDates() === true){
                    if ($(".carrito").text() == "") {
                        if ($("#forfait-junior-familiar").val() != 0 && $("#junfaDias option:selected").html() != "Elige una opcion") {
                            $(".carrito").html("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#forfait-junior-familiar").val() + " Forfait junior-Familiar ");
                            $("#" + cont).append($("#junfaDias").val() + " Dias" + "");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='forfaits[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'forfait','junfam',$("#forfait-junior-familiar").val(),$("#junfaDias").val());
                        } else {
                            alert("Debes ingresar una cantidad y un bono");
                        }

                    } else {
                        if ($("#forfait-junior-familiar").val() != 0 && $("#junfaDias option:selected").html() != "Elige una opcion") {
                            $(".carrito").append("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#forfait-junior-familiar").val() + " Forfait junior-Familiar ");
                            $("#" + cont).append($("#junfaDias").val() + " Dias" + "");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='forfaits[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'forfaits','junfam',$("#forfait-junior-familiar").val(),$("#junfaDias").val());
                        } else {
                            alert("Debes ingresar una cantidad y un bono");
                        }
                    }
                    cont++;
                }
            });
        });

        // Material
        $(document).ready(function () {
            $("input[name$='material']").click(function () {
                var test = $(this).val();

                $("div.desc").hide();
                $("#material" + test).show();
            });

            // Funcion de creacion de carrito Material
            $("#botonpack").click(function () {

                if(checkForfaitDates() === true){
                    if ($(".carrito").text() == "") {
                        if ($("#material-pack-cant").val() != 0 && $("#packtipo option:selected").html() != "Tipo" && $("#packdias option:selected").html() != "Dias") {
                            $(".carrito").html("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#material-pack-cant").val() + " Alquiler material " + " Packs, Tipo ");
                            $("#" + cont).append($("#packtipo").val() + ",");
                            $("#" + cont).append($("#packdias").val() + " Dias ");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='material[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'material','material-pack',$("#material-pack-cant").val(),$("#packdias").val(),$("#packtipo").val(),$("#esquisgama").val());
                        } else {
                            alert("Debes ingresar una Cantidad ,Tipo y Dias");
                        }

                    } else {
                        if ($("#material-pack-cant").val() != 0 && $("#packtipo option:selected").html() != "Tipo" && $("#packdias option:selected").html() != "Dias") {
                            $(".carrito").append("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#material-pack-cant").val() + " Packs, Tipo ");
                            $("#" + cont).append($("#packtipo").val() + ",");
                            $("#" + cont).append($("#packdias").val() + " Dias ");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='material[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'material','material-pack',$("#material-pack-cant").val(),$("#packdias").val(),$("#packtipo").val(),$("#esquisgama").val());
                        } else {
                            alert("Debes ingresar una Cantidad ,Tipo y Dias");
                        }
                    }
                    cont++;
                }
            });

            $("#botonesquis").click(function () {

                if(checkForfaitDates() === true){
                    if ($(".carrito").text() == "") {
                        if ($("#material-Esquis-cant").val() != 0 && $("#EsquisDias option:selected").html() != "Dias" && $("#esquitipo option:selected").html() != "Tipo" && $("#esquisgama option:selected").html() != "Gama") {
                            $(".carrito").html("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button>");
                            $("#" + cont).append($("#material-Esquis-cant").val() + " Alquiler material " + $("#esquitipo").val() + " ,");
                            $("#" + cont).append($("#EsquisDias").val() + " Dias,Gama  ");
                            $("#" + cont).append($("#esquisgama").val());
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='material[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'material','esquis',$("#material-Esquis-cant").val(),$("#EsquisDias").val(),$("#esquisgama").val());
                        } else {
                            alert("Debes elegir, Cantidad , Dias , Tipo y Gama ");
                        }

                    } else {
                        if ($("#material-Esquis-cant").val() != 0 && $("#EsquisDias option:selected").html() != "Dias" && $("#esquitipo option:selected").html() != "Tipo" && $("#esquisgama option:selected").html() != "Gama") {
                            $(".carrito").append("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button>");
                            $("#" + cont).append($("#material-Esquis-cant").val() + " Alquiler material " + $("#esquitipo").val() + " ,");
                            $("#" + cont).append($("#EsquisDias").val() + " Dias,Gama  ");
                            $("#" + cont).append($("#esquisgama").val());
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='material[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'material','esquis',$("#material-Esquis-cant").val(),$("#EsquisDias").val(),$("#esquisgama").val());
                        } else {
                            alert("Debes elegir, Cantidad , Dias , Tipo y Gama ");
                        }
                    }
                    cont++;
                }
                
            });

            $("#botonsnow").click(function () {

                if(checkForfaitDates() === true){
                    if ($(".carrito").text() == "") {
                        if ($("#material-snow-cant").val() != 0 && $("#snowDias option:selected").html() != "Dias" && $("#snowtipo option:selected").html() != "Tipo" && $("#snowgama option:selected").html() != "Gama") {
                            $(".carrito").html("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#material-snow-cant").val() + " Alquiler material " + $("#snowtipo").val() + " ,");
                            $("#" + cont).append($("#snowDias").val() + " Dias,Gama ");
                            $("#" + cont).append($("#snowgama").val());
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='material[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'material','snow',$("#material-snow-cant").val(),$("#snowDias").val(),$("#snowtipo").val(),$("#snowgama").val());
                        } else {
                            alert("Debes elegir, Cantidad , Dias , Tipo y Gama ");
                        }

                    } else {
                        if ($("#material-snow-cant").val() != 0 && $("#snowDias option:selected").html() != "Dias" && $("#snowtipo option:selected").html() != "Tipo" && $("#snowgama option:selected").html() != "Gama") {
                            $(".carrito").append("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#material-snow-cant").val() + " Alquiler material " + $("#snowtipo").val() + " ,");
                            $("#" + cont).append($("#snowDias").val() + " Dias,Gama ");
                            $("#" + cont).append($("#snowgama").val());
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='material[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'material','snow',$("#material-snow-cant").val(),$("#snowDias").val(),$("#snowtipo").val(),$("#snowgama").val());
                        } else {
                            alert("Debes elegir, Cantidad , Dias , Tipo y Gama ");
                        }
                    }
                    cont++;
                }
                
            });

            $("#botonblade").click(function () {
            
            
                if(checkForfaitDates() === true){
                    if ($(".carrito").text() == "") {
                        if ($("#material-blade-cant").val() != 0 && $("#bladeDias option:selected").html() != "Dias" && $("#bladeTipo option:selected").html() != "Tipo") {
                            $(".carrito").html("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#material-blade-cant").val() + " Alquiler material " + $("#bladeTipo").val() + ", ");
                            $("#" + cont).append($("#bladeDias").val() + " Dias,Tipo ");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='material[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'material','blade',$("#material-blade-cant").val(),$("#bladeDias").val());
                        } else {
                            alert("Debes elegir, Cantidad , Dias y Tipo  ");
                        }

                    } else {
                        if ($("#material-blade-cant").val() != 0 && $("#bladeDias option:selected").html() != "Dias" && $("#bladeTipo option:selected").html() != "Tipo") {
                            $(".carrito").append("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            $("#" + cont).append($("#material-blade-cant").val() + " Alquiler material " + $("#bladeTipo").val() + " ,");
                            $("#" + cont).append($("#bladeDias").val() + " Dias,Tipo ");
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='material[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'material','blade',$("#material-blade-cant").val(),$("#bladeDias").val());
                        } else {
                            alert("Debes elegir, Cantidad , Dias y Tipo ");
                        }
                    }
                    cont++;
                }
                
            });

            $("#botoncasco").click(function () {
            
                if(checkForfaitDates() === true){
                    if ($(".carrito").text() == "") {
                        if ($("#material-casco-cant").val() != 0 && $("#cascoDias option:selected").html() != "Dias" && $("#cascoTipo option:selected").html() != "Tipo") {
                            $(".carrito").html("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            if ($("#material-casco-cant").val() == 1) {
                                $("#" + cont).append($("#material-casco-cant").val() + " Alquiler material " + " casco,");
                            } else {
                                $("#" + cont).append($("#material-casco-cant").val() + " Alquiler material " + " cascos,");
                            }
                            $("#" + cont).append($("#cascoDias").val() + " Dias,Tipo ");
                            $("#" + cont).append($("#cascoTipo").val());
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='material[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'material','casco',$("#material-casco-cant").val(),$("#cascoDias").val(),$("#cascoTipo").val());
                        } else {
                            alert("Debes elegir, Cantidad , Dias y Tipo  ");
                        }

                    } else {
                        if ($("#material-casco-cant").val() != 0 && $("#cascoDias option:selected").html() != "Dias" && $("#cascoTipo option:selected").html() != "Tipo") {
                            $(".carrito").append("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> ");
                            if ($("#material-casco-cant").val() == 1) {
                                $("#" + cont).append($("#material-casco-cant").val() + " casco,");
                            } else {
                                $("#" + cont).append($("#material-casco-cant").val() + " cascos,");
                            }
                            $("#" + cont).append($("#cascoDias").val() + " Dias,Tipo ");
                            $("#" + cont).append($("#cascoTipo").val());
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='material[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'material','casco',$("#material-casco-cant").val(),$("#cascoDias").val(),$("#cascoTipo").val());
                        } else {
                            alert("Debes elegir, Cantidad , Dias y Tipo ");
                        }
                    }
                    cont++;
                }
            });
        });

        //Clases
        $(document).ready(function () {
            $("div.clase").click(function () {
                var test = $(this).attr('data-value');


                $("div.desc").hide();
                $("#clase" + test).show();

                $("div.clase").each(function (index, el) {
                    $(this).css('border', 'none');
                });

                $(this).css('border', '3px solid #00b4e9');
            });

            $("#botonparticular").click(function () {
                
                if(checkForfaitDates() === true){
                    if ($(".carrito").text() == "") {
                        if ($("#clase-particular-cant").val() != 0 && $("#clasehora option:selected").html() != "Hora de inicio" && $("#clasetipo option:selected").html() != "Clase" && $("#clasehoras option:selected").html() != "Nº de horas" && $("#claseprofesor option:selected").html() != "Profesor" && $("#claseidioma option:selected").html() != "Idioma") {

                            $(".carrito").html("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> Clase Particular ");
                            $("#" + cont).append($("#clase-particular-cant").val() + " Personas,a las  ");
                            $("#" + cont).append($("#clasehora").val() + ",");
                            $("#" + cont).append($("#clasehoras").val() + " hora/as, para  ");
                            $("#" + cont).append($("#clasetipo").val() + ", en  ");
                            $("#" + cont).append($("#claseidioma").val());
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='classes[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'classes','particulares',$("#clase-particular-cant").val(),$("#clasehoras").val().split(' ')[0],$("#clasetipo").val());
                        } else {
                            alert("Debes ingresar una Cantidad ,Tipo y Dias");
                        }

                    } else {
                        if ($("#clase-particular-cant").val() != 0 && $("#clasehora option:selected").html() != "Hora de inicio" && $("#clasetipo option:selected").html() != "Clase" && $("#clasehoras option:selected").html() != "Nº de horas" && $("#claseprofesor option:selected").html() != "Profesor" && $("#claseidioma option:selected").html() != "Idioma") {

                            $(".carrito").append("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> Clase Particular ");
                            $("#" + cont).append($("#clase-particular-cant").val() + " Personas,a las  ");
                            $("#" + cont).append($("#clasehora").val() + ",");
                            $("#" + cont).append($("#clasehoras").val() + " hora/as, para  ");
                            $("#" + cont).append($("#clasetipo").val() + ", en  ");
                            $("#" + cont).append($("#claseidioma").val());
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='classes[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'classes','particulares',$("#clase-particular-cant").val(),$("#clasehoras").val().split(' ')[0],$("#clasetipo").val());
                        } else {
                            alert("Debes ingresar una Cantidad ,Tipo y Dias");
                        }
                    }
                    cont++;
                }
                
            });

            $("#botoncolectivo").click(function () {

                if(checkForfaitDates() === true){
                    if ($(".carrito").text() == "") {
                        if ($("#clase-colectivo-cant").val() != 0 && $("#colectipo option:selected").html() != "Clase" && $("#colecprofesor option:selected").html() != "Profesor" && $("#colecidioma option:selected").html() != "Idioma") {

                            $(".carrito").html("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> Clase Colectiva ");
                            $("#" + cont).append($("#colecDias").val() + " ,para ");
                            $("#" + cont).append($("#clase-colectivo-cant").val() + " persona/as, para ");
                            $("#" + cont).append($("#colectipo").val() + ", en  ");
                            $("#" + cont).append($("#colecidioma").val());
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='classes[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'classes','colectivas',$("#clase-colectivo-cant").val(),$("#colecDias").val().split(' ')[0],$("#colectipo").val());
                        } else {
                            alert("Debes ingresar una Cantidad ,Tipo y Dias");
                        }

                    } else {
                        if ($("#clase-colectivo-cant").val() != 0 && $("#colectipo option:selected").html() != "Clase" && $("#colecprofesor option:selected").html() != "Profesor" && $("#colecidioma option:selected").html() != "Idioma") {

                            $(".carrito").append("<div id='" + cont + "'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button> Clase Colectiva ");
                            $("#" + cont).append($("#colecDias").val() + ",para ");
                            $("#" + cont).append($("#clase-colectivo-cant").val() + " persona/as, para ");
                            $("#" + cont).append($("#colectipo").val() + ", en ");
                            $("#" + cont).append($("#colecidioma").val());
                            $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                            $("#" + cont).append("<input type='hidden' name='classes[" + cont + "]' value='" + $("#" + cont).text() + "'>");

                            $("button#btdel" + cont).on('click', function () {
                                $(this).parent().remove();
                            });

                            requestPrice(cont,'classes','colectivas',$("#clase-colectivo-cant").val(),$("#colecDias").val().split(' ')[0],$("#colectipo").val());
                        } else {
                            alert("Debes ingresar una Cantidad ,Tipo y Dias");
                        }
                    }
                    cont++;
                }
            });
            
//            $('select#forfatis_number').prop("disabled", true);

        });
        
        function launchAjaxCalcForfaitsPrices(prepare_forfaits_data){
//            console.log(prepare_forfaits_data);
            $.ajax({
//                headers: {
//                    'X-CSRF-TOKEN': 
//                },
                type: "POST",
                url: "/ajax/forfaits/requestPriceForfaits",
                data: {requests:prepare_forfaits_data},
                dataType:'json',
//                async: false,
                success: function(response){
                    console.log(response);
                    $('.forfaits_request').remove();
                    
                    $.each(response.products.items,function(key,item){

                        $(".carrito").append("<div id='" + cont + "' class='forfaits_request'><button name='btdel' id='btdel" + cont + "' class='icon-remove-sign btn-danger'  type='button' style='border-radius:20px'></button>");
                        $("#" + cont).append('<span class="carrito_forfait_request">'+item.name+'</span>');
                        
                        $("#" + cont).append("<input type='hidden' class='forfaits_request' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                        $("#" + cont).append("<input type='hidden' class='forfaits_request' name='forfaits[" + cont + "]' value='" + $("#" + cont).text() + "'>");
                        $("#" + cont).append("<input type='hidden' class='forfaits_request' name='prices["+cont+"]' value='"+item.price+"'></div><br/>");
                        
                        cont++;
                    });

                    
//                    console.log(price);
//                    $("#" + cont).append(" - "+price+"&euro;");
////                    console.log($('input[name="carrito['+cont+']"]').val());
//                    input_cont = $('input[name="carrito['+cont+']"]');
//                    $('input[name="carrito['+cont+']"]').val(input_cont.val()+" - "+price+"&euro;");
//                    
//                    
//                    $("#" + cont).append("<input type='hidden' name='carrito[" + cont + "]' value='" + $("#" + cont).text() + "'>");
//                    $("#" + cont).append("<input type='hidden' name='forfaits[" + cont + "]' value='" + $("#" + cont).text() + "'>");
//                    $("#" + cont).append("<input type='hidden' name='prices["+cont+"]' value='"+response+"'>");
                    
                    
                },
                error: function(response){
//                    console.log(response);
                }
            });
        }
        
        function calc_days_diff(start_date,end_date){
            var start = moment(start_date, "DD-MM-YYYY");
            var end = moment(end_date, "DD-MM-YYYY");
            var diff = (end.diff(start, 'days')+1);
            
            if(diff == 1){
                return 0;
            }else{
                return diff;
            }
        }
        
        function set_dates(){
            start_date = $('div#requests_header input[name="date-entrada"]').val();
            end_date = $('div#requests_header input[name="date-salida"]').val();
            
//            console.log(start_date);
//            console.log(end_date);
            
            $('div#requests_container div.request_row input.datepicker_init_start_date').val(start_date);
            $('div#requests_container div.request_row input.datepicker_init_end_date').val(end_date);

            if(start_date.length > 0 && end_date.length > 0){
//                console.log(calc_days_diff(start_date,end_date));
                $('div#requests_container div.request_row input.request_days').val(calc_days_diff(start_date,end_date));
            }
        }
        
        function prepare_forfaits_data(){
            
            forfaits_data = {};
            
            c = 0;
            $('div#requests_container').find('div.request_row').each(function(){
                forfaits_data[c] = {};

                forfaits_data[c].start_date = $(this).find('input#date-entrada').val();
                forfaits_data[c].end_date = $(this).find('input#date-salida').val();
                forfaits_data[c].days = $(this).find('input.request_days').val();
                forfaits_data[c].years = $(this).find('input.request_years').val();
                c++;
            });
            
            return forfaits_data;
        }
        
        function bind_extra_datepicker_changes(){
            $('div#requests_container div.request_row input.datepicker_init_start_date, div#requests_container div.request_row input.datepicker_init_end_date').on('change',function(){

                parent_div = $(this).parent('div').parent('div');

                start_date = parent_div.find('input.datepicker_init_start_date').val();
                end_date = parent_div.find('input.datepicker_init_end_date').val();

                if(start_date.length > 0 && end_date.length > 0){
                    parent_div.find('input.request_days').val(calc_days_diff(start_date,end_date));
                }

            });
        }

        $('select#forfatis_number').on('change',function(){

            forfaits_number = $(this).find('option:selected').val();
            actual_requests = $('div#requests_container').find('div.request_row');
            actual_requests_length = actual_requests.length;
            requests_needed = forfaits_number-actual_requests_length;

            if(requests_needed > 0){
                for(x=1;x<=requests_needed;x++){
                    counter = x+actual_requests_length;
                    request_row = $('div#request_row_div').clone();
                    request_row.find('span.forfait_number').text(counter);
                    request_row.find('input[name="date-entrada-template"]').attr('name','date-entrada-'+counter);
                    request_row.find('input[name="date-salida-template"]').attr('name','date-salida-'+counter);
                    $('div#requests_container').append(request_row.html());
                }
            }else if(requests_needed == 0){
                $('div#requests_container div.request_row').remove();
            }else if(requests_needed < 0){
                requests_rows = $('div#requests_container').find('div.request_row');

                for(x=0;x<Math.abs(requests_needed);x++){
                    requests_rows[(actual_requests_length-1)-x].remove();
                }
            }
            
//            console.log(forfaits_number);
            if(forfaits_number > 0){
                $('div#requests_button').show();
            }else{
                $('div#requests_button').hide();
            }

            set_dates();
            instance_extra_datepickers();
            bind_extra_datepicker_changes();
        });
        
        $('div#requests_header input[name="date-entrada"], div#requests_header input[name="date-salida"]').change(function(){
            if($('input[name="date-entrada"]').val().length > 0 && $('input[name="date-salida"]').val().length > 0){
//                $('select#forfatis_number').prop("disabled", false);
//                $('select#forfatis_number').removeAttr("disabled");
                set_dates();
            }
        });

        $('input.datepicker_init_start_date, input.datepicker_init_end_date').change(function(){
//            console.log('test');
            parent_div = $(this).parent('div').parent('div');
            
            start_date = parent_div.find('input.datepicker_init_start_date').val();
            end_date = parent_div.find('input.datepicker_init_end_date').val();

            if(start_date.length > 0 && end_date.length > 0){
                parent_div.find('input.request_days').val(calc_days_diff(start_date,end_date));
            }
        });

        $('button#request_button').click(function(){
            
            status = true;
            
            $('div#requests_container').find('div.request_row').each(function(){
                
                forfait_data_start_date = $(this).find('input#date-entrada');
                forfait_data_end_date = $(this).find('input#date-salida');
                forfait_data_days = $(this).find('input.request_days');
                forfait_data_years = $(this).find('input.request_years');
                
                forfait_data_start_date_value = forfait_data_start_date.val();
                forfait_data_end_date_value = forfait_data_end_date.val();
                forfait_data_days_value = forfait_data_days.val();
                forfait_data_years_value = forfait_data_years.val();
                
                if( forfait_data_start_date_value.length == 0){
                    forfait_data_start_date.attr('style','border: 1px solid red;');
                }else{
                    forfait_data_start_date.removeAttr('style');
                }
                if( forfait_data_end_date_value.length == 0){
                    forfait_data_end_date.attr('style','border: 1px solid red;');
                }else{
                    forfait_data_end_date.removeAttr('style');
                }
                if( forfait_data_days_value.length == 0){
                    forfait_data_days.attr('style','border: 1px solid red;');
                }else{
                    forfait_data_days.removeAttr('style');
                }
                if( forfait_data_years_value.length == 0){
                    forfait_data_years.attr('style','border: 1px solid red;');
                }else{
                    forfait_data_years.removeAttr('style');
                }

                if( forfait_data_start_date_value.length == 0 ||
                    forfait_data_end_date_value.length == 0 ||
                    forfait_data_days_value.length == 0 ||
                    forfait_data_years_value.length == 0 ){
                    status = false;
                }
            });

            if(status == 'true'){
                forfaits_data = prepare_forfaits_data();
                launchAjaxCalcForfaitsPrices(forfaits_data);
            }else{
//                console.log('fail');
            }
            
        });
        
        $(window).load(function(){
            $('input.datepicker_init_start_date, input.datepicker_init_end_date').on('click',function(){
                console.log('click');
                console.log($('div.daterangepicker'));
                input_div = $(this);
                $('div.daterangepicker').css('top','20%');
                $('div.daterangepicker').css('position','fixed');

                $([document.documentElement, document.body]).animate({
                    scrollTop: input_div.parent('div').parent('div').offset().top
                }, 2000);
            });
        });

    </script>

    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="js/solo-formulario-reservas/datepicker/daterange.datepicker.js"></script>
    <script type="text/javascript" src="js/solo-formulario-reservas/codigo-desarrollo-form.js"></script>
</body>
</html>