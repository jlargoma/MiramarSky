<?php   use \Carbon\Carbon;
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
@extends('layouts.admin-master')

@section('title') Encuestas de satisfacción - miramarski @endsection

@section('externalScripts') 

    

@endsection

@section('content')



<div class="container-fluid padding-25 sm-padding-10">
    <div class="row push-20">
        <div class="container">
            <div class="row push-20">
                <div class="col-md-8 push-20">
                    <h2 class="text-center">RESULTADOS <span class="font-w800">ENCUESTA DE SATISFACCIÓN</span></h2>
                </div>
                <div class="col-md-2" style="padding-top: 10px;">
                    <select id="fecha" class="form-control minimal">
                        <?php $fecha = $date->copy()->SubYear(2); ?>
                        <?php if ($fecha->copy()->format('Y') < 2015): ?>
                            <?php $fecha = new Carbon('first day of September 2015'); ?>
                        <?php endif ?>

                        <?php for ($i=1; $i <= 3; $i++): ?>                           
                            <option value="<?php echo $fecha->copy()->format('Y'); ?>" {{ $date->copy()->format('Y') == $fecha->format('Y') ? 'selected' : '' }}>
                                <?php echo $fecha->copy()->format('Y')."-".$fecha->copy()->addYear()->format('Y'); ?> 
                            </option>
                            <?php $fecha->addYear(); ?>
                        <?php endfor; ?>
                    </select>     
                </div>
                <div class="col-md-2" style="padding-top: 10px;">
                    <select id="apto" class="form-control minimal">
                        <option value="all" <?php if ($apto == 'all'){ echo "selected";}?>>Todos</option> 
                        <?php foreach ($rooms as $key => $room): ?>
                            <option value="<?php echo $room->id ?>" <?php if ($apto == $room->id){ echo "selected";}?>>
                                <?php echo $room->nameRoom ?>
                            </option>            
                       <?php endforeach ?>                   
                           
                    </select>     
                </div>
            </div>
            <div class="row bg-white">
                <h2 class=" text-center font-w300 font-montserrat all-caps">
                    Valoraciones recibidas
                    <?php if ($apto != 'all'): ?>
                        <?php $room = \App\Rooms::find($apto); ?>
                        para <span class="font-w800"><?php echo $room->nameRoom ?></span>
                    <?php endif ?>
                </h2>
                <div class="text-center push-10">
                    <h2 class="push-0 text-center no-margin p-b-5 text-white"><?php echo $encuestas ?> / año</h2>
                </div>
            </div>
            <div class="row push-20 bg-white">  
                <?php foreach ($questions as $key => $question): ?>
                    
                    <?php if ($apto != 'all'): ?>
                        <?php $resultsQuestion =  \App\Http\Controllers\QuestionsController::getDataByQuestion($question->id, $apto , $date->copy()->format('Y')) ?> 
                    <?php else: ?>
                        <?php $resultsQuestion =  \App\Http\Controllers\QuestionsController::getDataByQuestion($question->id, "" , $date->copy()->format('Y')) ?> 
                    <?php endif ?>
                    <div class="col-md-3 push-20" >
                        <div class="col-xs-12" style="background-color: green; height: 110px">
                            <div class="col-xs-12">
                                <div class="row">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h3 class=" text-center text-white fs-16 all-caps" style="line-height: 1; letter-spacing: -1px">
                                                <?php echo $question->question ?>
                                            </h3>
                                            <div class="col-md-12 text-center push-10">
                                                <h2 class="push-0 text-center no-margin p-b-5 text-white">
                                                    <i class="fa fa-star"></i> <span style="font-size: 24px;"><?php echo $resultsQuestion['avg'] ?> de Media</span>
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            <div class="row push-20 bg-white"> 
                <div class="col-xs-12 push-10">
                    <h2 class=" text-center font-w300 font-montserrat all-caps">
                        Comentarios
                        <?php if ($apto != 'all'): ?>
                            para <span class="font-w800"><?php echo $room->nameRoom ?></span>
                        <?php endif ?>
                    </h2>
                </div>
                <?php foreach ($comments as $key => $comment): ?>
                    <?php $room = \App\Rooms::find($comment->room_id); ?>
                
                    <div class="col-lg-4 col-xs-12 push-20" style="padding: 10px;">
                        <div class="row" style="padding: 10px;border: 1px solid #e2e2e2; ">
                            <div class="row-xs-height">
                                <div class="social-user-profile col-xs-height text-center col-top">
                                    <div class="thumbnail-wrapper d48 circular bordered b-white">
                                        <img alt="Avatar" width="55" height="55" data-src-retina="/assets/img/profiles/b.jpg" data-src="/assets/img/profiles/b.jpg" src="/assets/img/profiles/b.jpg">
                                    </div>
                                </div>
                                <div class="col-xs-height p-l-20">
                                    <h3 class="no-margin p-b-5">Anónimo</h3>
                                    <p class="no-margin fs-16"><?php echo $comment->rate; ?>
                                    </p>
                                    <p class="small">para <span class="font-w800"><?php echo $room->nameRoom ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>

            </div>
        </div>
    </div>
    
</div>


@endsection

@section('scripts')
    <script type="text/javascript">
            
        $('#fecha').change(function(event) {

            var year = $(this).val();
            var apto = $('#apto').val();
            if (apto == 'all') {
                window.location = '/admin/encuestas/'+year;
            } else {
                window.location = '/admin/encuestas/'+year+'/'+apto;
            }
            
        });

        $('#apto').change(function(event) {
            var actual = '<?php echo date("Y") ?>';
            var apto = $(this).val();
            var year= $('#fecha').val();
            if (apto == 'all') {
                window.location = '/admin/encuestas/';
            } else {
                if (year != actual) {
                    window.location = '/admin/encuestas/'+year+'/'+apto;
                } else {
                    window.location = '/admin/encuestas/'+year+'/'+apto;
                }
                
            }
            
        });

    </script>
@endsection