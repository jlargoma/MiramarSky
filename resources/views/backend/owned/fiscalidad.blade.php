@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts') 

    <link href="/assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />

@endsection
    
@section('content')
<?php use \Carbon\Carbon; 
setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>

<?php if (!$mobile->isMobile()): ?>
    <div class="container-fluid padding-10 sm-padding-10">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="col-md-4 m-t-20">
                    <div class="col-md-3">
                        <a class="btn btn-success btn-cons text-white" href="{{ url('') }}/<?php echo str_replace('fiscalidad', 'operativa', Request::path()) ?>"  >Opertaiva</a>
                    </div>
                    <div class="col-md-3">
                        <a class="btn btn-success btn-cons text-white" href="{{ url('') }}/<?php echo str_replace('fiscalidad', 'tarifas', Request::path()) ?>" >Tarifas</a>
                    </div>
                    <div class="col-md-3">
                        <a class="btn btn-success btn-cons text-white" href="{{ url('') }}/<?php echo str_replace('fiscalidad', 'descuentos', Request::path()) ?>">Descuentos</a>
                    </div>
                    <div class="col-md-3">
                        <a class="btn btn-success btn-cons text-white" href="{{ url('') }}" disabled>Fiscalidad</a>
                    </div>
                    
                </div>
                <div class="col-md-1 pull-right">
                    <div class="col-md-3 m-t-20">
                        
                        <a class="btn btn-success btn-cons text-white" href="{{ url('') }}/<?php echo str_replace('fiscalidad', '', Request::path()) ?>">Volver</a>
                    </div>
                </div>        
            </div>

            <div class="col-md-12">
                <div align="center"><font size="10px" color="blue"><strong>Fiscalidad</strong></font></div>
                <br /><br /><br />
                <div class="container m-t-20">
                    <h5><b>LA FISCALIDAD DE LAS VIVIENDAS  CON FINES TURÍSTICOS</b></h5>
                    <p>
                        Desde hace varios años y con la ayuda de diversas aplicaciones informáticas, se ha incrementado exponencialmente el arrendamiento de viviendas turísticas, lo que supone la generación de nuevas rentas por parte de los ciudadanos que debe declarase correctamente en los modelos correspondientes.<br><br> 
                        
                        Este tipo de servicio puede ser gestionado por el propietario o por medio alguna de las empresas Intermediaria que gestiona estos inmuebles.<br><br> 

                        <b>A)  I.R.P.F:</b> En relación al Impuesto sobre la Renta de las Personas Físicas En nuestro caso el propietario no está  prestando a los inquilinos servicios que puedan considerase hoteleros (no se considera hotelero el servicio de limpieza y cambio de ajuar antes y después de la cesión).<br><br>  
                        En este supuesto que es el más común, se alternan periodos cortos de arrendamiento con otros en que el inmueble está vacío:
                        En el periodo durante el que la vivienda está alquilada se declararán los ingresos íntegros y se deducirán los gastos necesarios para su alquiler.<br><br> 
                         
                        El rendimiento neto por el que habrá que tributar será el resultante de restar de los ingresos totales los gastos necesarios para obtenerlos, en proporción a los días alquilados (IBI, intereses de la hipoteca, tasa de basura, seguros que cubren riesgos de la vivienda, comunidad, amortización del inmueble o de los enseres que haya en él siempre que respondan a una depreciación efectiva e incluso luz, agua, gas).<br><br> 

                        <b><u>La diferencia entre estos ingresos y gastos deberá figurar en la Declaración de la Renta como "rendimientos del capital inmobiliario"</u></b>, se integraran en la base imponible general y tributaran al tipo que nos corresponda en cada caso.<br><br>
                                              
                        <b>B) IVA:</b> En lo que se refiere al Impuesto sobre el Valor Añadido: <u>es un servicio de arrendamiento  prestado directamente al inquilino por el titular</u>,<u><b> por lo tanto es una operación exenta de I.V.A.</b></u><br><br> 
                         
                        El propietario está cede la vivienda a través de un Intermediario inmobiliario que le cobra una comisión o gastos de gestión al cliente final, <u>el arrendamiento está exento de IVA </u>…<u>el Intermediario inmobiliario si deberá emitir una factura sujeta al IVA al 21% por su intermediación.</u><br><br>

                        Dado que se trata de un tema de gran actualidad y con una amplia casuística si en algún caso lo requiere te ayudamos a examinar tu situación en concreto.<br><br>
                    </p>
                    <h5><b>REGISTRO  VIVIENDA TUISTICA JUNTA DE ANDALUCIA</b></h5>

                    <p>
                        Desde hace algún tiempo es obligatorio sacar el número de registro de Vivienda Turística<br><br>


                        Se consideran viviendas con fines turísticos todas aquellas ubicadas en <b>suelo de uso residencial</b> donde se ofrece, mediante precio, <b>el servicio de alojamiento de forma habitual y con fines turísticos,</b> es decir, con comercialización y <b>promoción a través de los canales de oferta</b> propios de este ámbito (agencias de viaje, empresas mediadoras u organizadoras y medios que incluyen posibilidad de reserva).<br><br>


                        <b>¿Qué requisitos tiene que cumplir la vivienda?</b><br><br>

                        Tiene que disponer de licencia de ocupación. Tener las habitaciones con ventilación al exterior y con sistemas de oscurecimiento. Además tienen que contar con un sistema de climatización, de frío y calor, en el salón y dormitorios. La norma exige un botiquín de primeros auxilios, disponer de información turística de la zona, de hojas y quejas de reclamación y de un servicio de limpieza a la entrada y salida de nuevos clientes. Además de lencería de casa, con un juego adicional, y de enseres y electrodomésticos. También el turista debe tener un teléfono de contacto para comunicar cualquier incidencia y debe de conocer las normas que rigen en la comunidad de vecinos. La capacidad máxima por inmueble completo no puede exceder de 15 personas.<br><br>


                        <b>¿Hay que hacer un contrato a cada turista, una factura o pedir el DNI para el registro de la Policía?</b><br><br>

                        La normativa obliga a hacer un contrato a cada inquilino, aunque éste permanezca una noche. Además, este acuerdo escrito deberá ser guardado durante un año por si es requerido por los inspectores de Turismo. <br>

                        En este documento deberá constar el nombre de la persona, el número de personas que van a ocupar el piso, las fechas de entrada y salida, el precio total de la estancia y el número de teléfono que se le proporciona al cliente para comunicar incidencias.<br>
                        Los usuarios del piso deberán presentar su documento de identificación a los efectos de cumplimentar el correspondiente parte de entrada.<br><br>


                        <u>Si decides como propietario dar de alta tu vivienda en este registro y ayuda de un profesional te ponemos en contacto con una asesoría especializada para que lo gestionen a un precio módico.</u><br><br>


                    </p>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="container-fluid padding-10 sm-padding-10">
        <div class="row">
            <div class="col-md-12 text-left  push-20">
                <div class="col-md-4 m-t-20">
                    <div class="col-md-3  push-20">
                            
                        <a class="btn btn-success btn-cons text-white" href="{{ url('') }}/<?php echo str_replace('fiscalidad', '', Request::path()) ?>">Volver</a>
                    </div>   
                    <div class="col-xs-6 push-20">
                        <a class="btn btn-success btn-cons text-white" href="{{ url('') }}/<?php echo str_replace('fiscalidad', 'operativa', Request::path()) ?>"  >Opertaiva</a>
                    </div>
                    <div class="col-xs-6 push-20">
                        <a class="btn btn-success btn-cons text-white" href="{{ url('') }}/<?php echo str_replace('fiscalidad', 'tarifas', Request::path()) ?>" >Tarifas</a>
                    </div>
                    <div class="col-xs-6 push-20">
                        <a class="btn btn-success btn-cons text-white" href="{{ url('') }}/<?php echo str_replace('fiscalidad', 'descuentos', Request::path()) ?>">Descuentos</a>
                    </div>
                    <div class="col-xs-6 push-20">
                        <a class="btn btn-success btn-cons text-white" href="{{ url('') }}" disabled>Fiscalidad</a>
                    </div>
                    
                </div>
                     
            </div>

            <div class="col-md-12">
                <div class="col-xs-12 push-20"><h2 class="text-center"><b>Fiscalidad</b></h2></div>
                <div class="container push-20">
                    <h5><b>LA FISCALIDAD DE LAS VIVIENDAS  CON FINES TURÍSTICOS</b></h5>
                    <p class="text-justify">
                        Desde hace varios años y con la ayuda de diversas aplicaciones informáticas, se ha incrementado exponencialmente el arrendamiento de viviendas turísticas, lo que supone la generación de nuevas rentas por parte de los ciudadanos que debe declarase correctamente en los modelos correspondientes.<br><br> 
                        
                        Este tipo de servicio puede ser gestionado por el propietario o por medio alguna de las empresas Intermediaria que gestiona estos inmuebles.<br><br> 

                        <b>A)  I.R.P.F:</b> En relación al Impuesto sobre la Renta de las Personas Físicas En nuestro caso el propietario no está  prestando a los inquilinos servicios que puedan considerase hoteleros (no se considera hotelero el servicio de limpieza y cambio de ajuar antes y después de la cesión).<br><br>  
                        En este supuesto que es el más común, se alternan periodos cortos de arrendamiento con otros en que el inmueble está vacío:
                        En el periodo durante el que la vivienda está alquilada se declararán los ingresos íntegros y se deducirán los gastos necesarios para su alquiler.<br><br> 
                         
                        El rendimiento neto por el que habrá que tributar será el resultante de restar de los ingresos totales los gastos necesarios para obtenerlos, en proporción a los días alquilados (IBI, intereses de la hipoteca, tasa de basura, seguros que cubren riesgos de la vivienda, comunidad, amortización del inmueble o de los enseres que haya en él siempre que respondan a una depreciación efectiva e incluso luz, agua, gas).<br><br> 

                        <b><u>La diferencia entre estos ingresos y gastos deberá figurar en la Declaración de la Renta como "rendimientos del capital inmobiliario"</u></b>, se integraran en la base imponible general y tributaran al tipo que nos corresponda en cada caso.<br><br>
                                              
                        <b>B) IVA:</b> En lo que se refiere al Impuesto sobre el Valor Añadido: <u>es un servicio de arrendamiento  prestado directamente al inquilino por el titular</u>,<u><b> por lo tanto es una operación exenta de I.V.A.</b></u><br><br> 
                         
                        El propietario está cede la vivienda a través de un Intermediario inmobiliario que le cobra una comisión o gastos de gestión al cliente final, <u>el arrendamiento está exento de IVA </u>…<u>el Intermediario inmobiliario si deberá emitir una factura sujeta al IVA al 21% por su intermediación.</u><br><br>

                        Dado que se trata de un tema de gran actualidad y con una amplia casuística si en algún caso lo requiere te ayudamos a examinar tu situación en concreto.<br><br>
                    </p>
                    <h5><b>REGISTRO  VIVIENDA TUISTICA JUNTA DE ANDALUCIA</b></h5>

                     <p class="text-justify">
                        Desde hace algún tiempo es obligatorio sacar el número de registro de Vivienda Turística<br><br>


                        Se consideran viviendas con fines turísticos todas aquellas ubicadas en <b>suelo de uso residencial</b> donde se ofrece, mediante precio, <b>el servicio de alojamiento de forma habitual y con fines turísticos,</b> es decir, con comercialización y <b>promoción a través de los canales de oferta</b> propios de este ámbito (agencias de viaje, empresas mediadoras u organizadoras y medios que incluyen posibilidad de reserva).<br><br>


                        <b>¿Qué requisitos tiene que cumplir la vivienda?</b><br><br>

                        Tiene que disponer de licencia de ocupación. Tener las habitaciones con ventilación al exterior y con sistemas de oscurecimiento. Además tienen que contar con un sistema de climatización, de frío y calor, en el salón y dormitorios. La norma exige un botiquín de primeros auxilios, disponer de información turística de la zona, de hojas y quejas de reclamación y de un servicio de limpieza a la entrada y salida de nuevos clientes. Además de lencería de casa, con un juego adicional, y de enseres y electrodomésticos. También el turista debe tener un teléfono de contacto para comunicar cualquier incidencia y debe de conocer las normas que rigen en la comunidad de vecinos. La capacidad máxima por inmueble completo no puede exceder de 15 personas.<br><br>


                        <b>¿Hay que hacer un contrato a cada turista, una factura o pedir el DNI para el registro de la Policía?</b><br><br>

                        La normativa obliga a hacer un contrato a cada inquilino, aunque éste permanezca una noche. Además, este acuerdo escrito deberá ser guardado durante un año por si es requerido por los inspectores de Turismo. <br>

                        En este documento deberá constar el nombre de la persona, el número de personas que van a ocupar el piso, las fechas de entrada y salida, el precio total de la estancia y el número de teléfono que se le proporciona al cliente para comunicar incidencias.<br>
                        Los usuarios del piso deberán presentar su documento de identificación a los efectos de cumplimentar el correspondiente parte de entrada.<br><br>


                        <u>Si decides como propietario dar de alta tu vivienda en este registro y ayuda de un profesional te ponemos en contacto con una asesoría especializada para que lo gestionen a un precio módico.</u><br><br>


                    </p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

@endsection

@section('scripts')
    
    <script src="/assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="/assets/plugins/datatables-responsive/js/lodash.min.js"></script>
    <script src="/assets/plugins/moment/moment.min.js"></script>

@endsection