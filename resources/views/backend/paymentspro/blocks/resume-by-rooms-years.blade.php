<h3 class="text-center font-w300">
    RESUMEN <span class="font-w800">PAGOS PROP</span>.
</h3>
<table class="table table-striped table-ingrPropYear">
    <thead>
        <tr>
            <th class="text-center bg-complete text-white" style="padding: 10px 5px;">
                Apart
            </th>
            <?php $auxYear = $year->year-2000; ?>
            <?php for ($i = 1; $i < 4; $i++) : ?>
                <th class="text-center bg-complete text-white" style="padding: 10px 5px;">
                    Temp. <?php echo $auxYear.'-'.($auxYear+1); ?>
                </th>
                <?php $auxYear--; ?>
            <?php endfor; ?>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $r) : ?>
            <tr>
                <td style="padding:5px !important;">
                <?php
                switch($r['semaf']){
                    case 'yellow':
                        echo '<span class="semaphore yellow"></span>';
                        break;
                    case 'green':
                        echo '<i class="fa fa-arrow-up"></i>';
                        break;
                    case 'red':
                            echo '<i class="fa fa-arrow-down red"></i>';
                        break;
                }
                ?>
                    <a class="historic-production" data-id="{{$r['id']}}" data-toggle="modal" data-target="#payments">
                        {{$r['name']}}
                    </a>
                </td>
                <?php $auxYear = $year->year; ?>
                <?php for ($i = 0; $i < 3; $i++) : ?>
                    <td class="text-center  costeApto bordes" style="padding: 10px 5px ;">
                    {{ moneda($r[$auxYear-$i])}}
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<div>
<table class="table table-striped table-ingrPropYear">
        <thead>
            <tr>
                <th class=" bg-complete text-white">Tipo de Apto</th>
                <th class="text-center bg-complete text-white">Media</th>
            </tr>
        </thead>
        <tbody>
        @foreach($media as $v)
        <tr>
            <td style="padding:5px !important;">{{$v['n']}}</td>
            <td class="text-center">{{moneda($v['v'])}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<style>
    .table-ingrPropYear{
        width: 500px;
    max-width: 98%;
    margin: 1em auto;
    }
    span.semaphore {
    display: inline-block;
    background-color: grey;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}
span.semaphore.green {
    background-color: #86d386;
}
span.semaphore.yellow {
    background-color: #e9e93b;
}
span.semaphore.red {
    background-color: #f39292;
}
</style>