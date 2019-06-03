<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
        <link href="{{ asset('/assets/plugins/bootstrapv3/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('/assets/plugins/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet" type="text/css" media="screen">
        <link rel="stylesheet" href="{{ asset('/frontend/css/components/daterangepicker.css')}}" type="text/css" />
    </head>
    <body>
        <div class="container">
            <div class="row" id="content-form">
                <form action="{{ route('api.proccess') }}" method="post" style="width: 100%;" id="api-form">
                    <div class="col-md-12 text-center" style="margin-bottom: 20px;">
                        <h2 class="text-center">
                            Consulta disponibilidad
                        </h2>
                    </div>
                    <div class="col-md-offset-4 col-md-4 text-center" style="margin-bottom: 20px;">
                        <div class="col-md-6">
                            <label for="name">Nombre</label>
                            <input class="form-control cliente" type="text" name="name">
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <input class="form-control cliente" type="email" name="email" >
                        </div>
                        <div class="col-md-6">
                            <label for="phone">Telefono</label>
                            <input class="form-control cliente" type="text" name="phone" >
                        </div>
                        <div class="col-md-6 col-xs-12 push-10">
                            <label for="dni">DNI</label>
                            <input class="form-control cliente" type="text" name="dni">
                        </div>
                        <div class="col-md-6 m-b-10">
                            <label for="dates"></label>
                            <input type="text" class="form-control" name="dates" id="dates" style="cursor: pointer;"
                            required/>
                        </div>
                        <div class="col-md-6 m-b-10">
                            <label for="pax"></label>
                            <select class="form-control" name="pax" id="pax" required>
                                <?php for ($i = $minMax->min; $i <= $minMax->max ; $i++): ?>
                                    <option value="{{ $i }}">{{ $i }} Pers </option>
                                <?php endfor?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button class="btn btn-success btn-rounded">
                            Consultar fechas
                        </button>
                    </div>
                </form>
            </div>
            <div class="row" id="content-info" style="display: none"></div>
        </div>

        <script src="//code.jquery.com/jquery.js"></script>
        <script type="text/javascript" src="{{asset('/frontend/js/components/moment.js')}}"></script>
        <script type="text/javascript" src="{{asset('/frontend/js/components/daterangepicker.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/custom-api.js')}}"></script>
    </body>
</html>