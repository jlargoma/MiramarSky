<?php use \Carbon\Carbon; ?>
<table>
    <tbody >
        <tr>
            <td style="text-align: center; font-weight: 800;">Nombre</td>
            <th class ="text-center bg-complete text-white" style="width: 3% !important;font-size:10px!important">&nbsp;&nbsp;&nbsp;Tipo&nbsp;&nbsp;&nbsp;</th>
            <td style="text-align: center; font-weight: 800;">Pax</td>
            <td style="text-align: center; font-weight: 800;">Apto</td>
            <td style="text-align: center; font-weight: 800;">IN - OUT</td>
            <td style="text-align: center; font-weight: 800;">Noches</td>
            <td style="text-align: center; font-weight: 800;">PVP</td>
            <td style="text-align: center; font-weight: 800;">BANCO</td>
            <td style="text-align: center; font-weight: 800;">CAJA</td>
            <td style="text-align: center; font-weight: 800;">Pendiente</td>
            <td style="text-align: center; font-weight: 800;">Ingreso Neto</td>
            <td style="text-align: center; font-weight: 800;">%Benef</td>
            <td style="text-align: center; font-weight: 800;">Coste Total</td>
            <td style="text-align: center; font-weight: 800;">Coste Apto</td>
            <td style="text-align: center; font-weight: 800;">Park</td>
            <td style="text-align: center; font-weight: 800;">Sup. Lujo</td>
            <td style="text-align: center; font-weight: 800;">Limp</td>
            <td style="text-align: center; font-weight: 800;">Agencia</td>
            <td style="text-align: center; font-weight: 800;">Extras</td>
            <td style="text-align: center; font-weight: 800;">TPV</td>
        </tr>
        <?php foreach ($books as $book): ?>
            <tr >
                <td>
                    <?php  echo $book->customer->name ?>

                </td>
                <td class="text-center">
                    <!-- type -->
                    
                    <?php
                        switch ($book->type_book){
                            case 2:
                                echo "C";
                                break;
                            case 7:
                                echo "P";
                                break;
                            case 8:
                                echo "A";
                                break;
                        }
                    ?>
                    
                </td>
                <td class="text-center">
                    <!-- pax -->
                    <?php echo $book->pax ?>
                </td>
                <td class="text-center">
                    <!-- apto -->

                    <?php echo $book->room->nameRoom ?>
                </td>
                <td class="text-center">
                    <?php
                        $start = Carbon::createFromFormat('Y-m-d',$book->start);
                        echo $start->formatLocalized('%d %b');
                    ?> -
                    <?php
                        $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                        echo $finish->formatLocalized('%d %b');
                    ?>
                </td>
                <td class="text-center">
                    <?php echo $book->nigths ?>
                </td>
                <td class="text-center coste">
                    {{$book->total_price}}

                </td>

                <td class="text-center coste">
                     {{$book->getPayment(2)+$book->getPayment(3)}}
                </td>
                <td class="text-center coste">
                    {{$book->getPayment(0)+$book->getPayment(1)}}
                </td>
                <td class="text-center coste pagos pendiente red " >

                    {{ $book->pending }}

                </td>
                <td class="text-center beneficio bi">
                    <?php $cost_total = $book->get_costeTotal();?>
                    <?php $total_price = $book->total_price?>
                    <?php $profit = $total_price-$cost_total;?>
                    <?php $inc_percent = $book->get_inc_percent()?>
                    {{$book->profit}}
                </td>

                <td class="text-center beneficio bf ">
                   <?php echo number_format($inc_percent,0,',','.') ?>
                </td>
                <td class="text-center coste bi ">
                    {{$cost_total}}
                </td>
                <td class="text-center coste">
                    {{$book->cost_apto}}
                </td>
                <td class="text-center coste">
                   {{$book->cost_park}}

                </td>
                <td class="text-center coste" >
                    {{($book->get_costLujo())}}
                </td>
                <td class="text-center coste">
                    {{$book->cost_limp}}
                </td>
                <td class="text-center coste ">
                    {{$book->PVPAgencia}}

                </td>
                <td class="text-center coste">
                    {{$book->extraCost}}
                </td>
                <td class="text-center coste bf">
                    {{$book->stripeCost}}
                    
                </td>

            </tr>
        <?php endforeach ?>

    </tbody>
</table>
