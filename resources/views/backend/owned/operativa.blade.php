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

<div class="container-fluid padding-10 sm-padding-10">
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="col-md-4 m-t-20">
                <div class="col-md-3">
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}" disabled >Opertaiva</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}/<?php echo str_replace('operativa', 'tarifas', Request::path()) ?>">Tarifas</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}/<?php echo str_replace('operativa', 'descuentos', Request::path()) ?>">Descuentos</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}/<?php echo str_replace('operativa', 'fiscalidad', Request::path()) ?>">Fiscalidad</a>
                </div>
                
            </div> 
            <div class="col-md-1 pull-right">
                <div class="col-md-3 m-t-20">
                    
                    <a class="btn btn-danger btn text-white" href="{{ url('') }}/<?php echo str_replace('operativa', '', Request::path()) ?>">Volver</a>
                </div>
            </div>       
        </div>

        <div class="col-md-12">
            <div align="center"><font size="10px" color="blue"><strong>OPERATIVA DE FUNCIONAMIENTO</strong></font></div>

            <div class="container m-t-20">
                <p>
                    La plataforma reservas se encargará de gestionar íntegramente de todas las tareas inherentes al alquiler:


                    <ul>
                        <li style="margin-bottom: 5px">Captación del cliente, a través de publicidad y bases de datos</li>
                        <li style="margin-bottom: 5px">Gestión del planning de ocupación de tu apartamento</li>
                        <li style="margin-bottom: 5px">Cobros de anticipos</li>
                        <li style="margin-bottom: 5px">Depósito de fianza</li>
                        <li style="margin-bottom: 5px">CHECK IN - Entrega de llaves</li>
                        <li style="margin-bottom: 5px">Atención al cliente durante su estancia</li>
                        <li style="margin-bottom: 5px">Gestión de peticiones adicionales ( clases de Ski, alquiler de material, reservas restaurante, servicios de limpieza adicionales…etc)</li>
                        <li style="margin-bottom: 5px">CHECK OUT – Revisión del apartamento y en su caso devolución de fianza</li>
                        <li style="margin-bottom: 5px">Limpieza profesional y preparación del mismo para el siguiente cliente.</li>
                        <li style="margin-bottom: 5px">Ropa de cama necesaria para tu vivienda</li>
                    </ul>

                    <strong><u>* Todos los viernes</u></strong> El propietario recibirá información actualizada sobre el <strong><u>plannning de ocupación</u></strong> de su apartamento de manera que tenga visibilidad total sobre el progreso de la temporada, adjunto un ejemplo del planning:

                    <br /><br />

                    En color rojo aparecen las reservas que tenemos señalizadas ( con cobro de señal)<br />
                    En color verde aparecen las reservas que tenemos bloqueadas ( durante 24/48 horas se les da a los huéspedes la posibilidad de realizar el ingreso)<br />
                    En color amarillo apareceran las reservas que tenemos bloqueadas para vuestro uso.

                    <br /><br />

                    <strong><u>* Mensualmente liquidaremos los ingresos que te corresponden</u></strong> y que puedes comprobar en esta url  (específica para tu apartamento y a la que
                    solo puedes acceder con usuario  contraseña)

                    <br /><br />

                    *Si los <strong><u>propietarios deciden ir a su apartamento</u></strong> para pasar unos días. <br />
                    Como tendrás acceso al planning de ocupación veras en que huecos puedes utilizar tu apartamento y podrás “reservarte” esos días para ti mismo.

                    <br /><br />

                    En función de los días que pases allí  podremos tener mayor o menor rentabilidad, necesitaremos saber si  dispondremos de la navidades para alquilarlo y así
                    poder evaluar si es interesante la propuesta para todas las partes.

                    <br /><br />

                    En el caso de que el propietario decida ir a su apartamento, obviamente no tendrá cargo alguno por nuestra parte, gestionaremos si lo requiere la entrega de llaves,
                    servicio de sabanas y toallas, servicio de limpieza profesional….etc, lo que se necesite.

                    <br /><br />

                    *<strong><u> El cargo por el servicio de limpieza y lavandería es de 50€</u></strong> , limpieza profesional 30€ y por la lavandería y ropa de cama 20€, es lo que nos cobran a nosotros.

                    <br /><br />

                    Estos servicios son opcionales, si los necesitáis para vuestra estancia, solo tenéis que pedírnoslos

                    <br /><br />

                    *<strong><u> Descuentos en forfaits, clases de Esquí o alquiler de material</u></strong><br /><br />
                    Ponemos a vuestra disposición los descuentos que hemos negociado a un precio especial.

                    <br /><br />

                    Este es un servicio de pago directo al proveedor, nosotros solo queremos ayudaros a que estéis contentos

                    <br /><br />

                    Recuerda que al menos <u>necesitan 48 horas de antelación</u> para poder tramitártelo

                    <br /><br />

                    Rellena el formulario y se pondrán en contacto contigo para <a href="http://www.apartamentosierranevada.net/forfait"> gestionar tu petición</a>
                </p>
            </div>
        </div>
    </div>
</div>
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