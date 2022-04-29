<div class="row">
    <div class="col-md-6">
    <?php
        $t_forfaits = $t_equipos = $t_clases = $t_otros = 0;
        $pvpTotalFF = 0;
        foreach($months_obj as $item) $pvpTotalFF += $item['value'];
        ?>

        <div class="table-responsive">
            <table class="table table-resumen">
                <thead>
                    <tr class="resume-head">
                        <th class="static">Concepto</th>
                        <th class="first-col">Total<br/> {{moneda($pvpTotalFF)}}</th>
                        @foreach($months_obj as $item)
                        <th>
                            {{$item['name']}} {{$item['year']}}
                            <br/> {{moneda($item['value'])}}
                        </th>
                        <?php
                        $t_forfaits += $item['data']['forfaits'];
                        $t_equipos  += $item['data']['equipos'];
                        $t_clases   += $item['data']['clases'];
                        $t_otros    += $item['data']['otros'];
                        ?>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="static">Forfaits</td>
                        <td class="first-col"><?php echo number_format($t_forfaits, 0, ',', '.') ?> €</td>
                        @foreach($months_obj as $item)
                        <td><?php echo number_format($item['data']['forfaits'], 0, ',', '.'); ?>€</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="static">Materiales</td>
                        <td class="first-col"><?php echo number_format($t_equipos, 0, ',', '.') ?> €</td>
                        @foreach($months_obj as $item)
                        <td><?php echo number_format($item['data']['equipos'], 0, ',', '.'); ?>€</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="static">Clases</td>
                        <td class="first-col"><?php echo number_format($t_clases, 0, ',', '.') ?> €</td>
                        @foreach($months_obj as $item)
                        <td><?php echo number_format($item['data']['clases'], 0, ',', '.'); ?>€</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="static">Otros</td>
                        <td class="first-col"><?php echo number_format($t_otros, 0, ',', '.') ?> €</td>
                        @foreach($months_obj as $item)
                        <td><?php echo number_format($item['data']['otros'], 0, ',', '.'); ?>€</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="box-errors" style="display:none;">
            <h3 class="text-danger">Errores Forfaits</h3>
            <?php
            if ($errors) :
                foreach ($errors as $er) :
            ?>
                    <div class="item-error row">
                        <div class="col-md-8">
                            <?php echo $er->detail; ?><br />
                            <small><?php echo date('d M H:i', strtotime($er->created_at)); ?></small>
                        </div>
                        <div class="col-md-4">
                            <a data-booking="<?php echo $er->book_id; ?>" class="openFF text-danger" title="Ir a Forfaits">
                                Ver Forfait
                            </a>
                        </div>

                    </div>
            <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 text-center">
        <canvas id="barChartMounth" style="width: 96%; height: 250px;"></canvas>
        
    </div>
    <div class="col-md-3 col-sm-6 text-center">
        <div class="table-responsive">
            <table class="table table-resumen">
                <thead>
                    <tr class="resume-head">
                        <th class="static">Concepto</th>
                        <th class="first-col"></th>
                        @foreach($yResume as $k=>$v)
                        <th>
                            {{$k}}
                            <br/> {{moneda(array_sum($v))}}
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="static">Forfaits</td>
                        <td class="first-col"></td>
                        @foreach($yResume as $k=>$v)
                        <td>{{moneda($v['forfaits'])}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="static">Materiales</td>
                        <td class="first-col"></td>
                        @foreach($yResume as $k=>$v)
                        <td>{{moneda($v['equipos'])}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="static">Clases</td>
                        <td class="first-col"></td>
                        @foreach($yResume as $k=>$v)
                        <td>{{moneda($v['clases'])}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="static">Otros</td>
                        <td class="first-col"></td>
                        @foreach($yResume as $k=>$v)
                        <td>{{moneda($v['otros'])}}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    
    </div>
</div>