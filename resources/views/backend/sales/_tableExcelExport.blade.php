<?php use \Carbon\Carbon; ?>
<table>
    <tbody >
        <tr>
            <td style="text-align: center; font-weight: 800;">Nombre</td>
            <td style="text-align: center; font-weight: 800;">Pax</td>
            <td style="text-align: center; font-weight: 800;">Apto</td>
            <td style="text-align: center; font-weight: 800;">IN - OUT</td>
            <td style="text-align: center; font-weight: 800;">Noches</td>
            <td style="text-align: center; font-weight: 800;">PVP</td>
            <td style="text-align: center; font-weight: 800;">Banco Jorg</td>
            <td style="text-align: center; font-weight: 800;">Banco Jaime</td>
            <td style="text-align: center; font-weight: 800;">Cash Jorge</td>
            <td style="text-align: center; font-weight: 800;">Cash Jaime</td>
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
            <td style="text-align: center; font-weight: 800;">Stripe</td>
            <td style="text-align: center; font-weight: 800;">Benef Jorge</td>
            <td style="text-align: center; font-weight: 800;">Benef Jaime</td>
        </tr>
        <?php foreach ($books as $book): ?>
            <tr >
                <td  style="text-align: center;">
                    <?php  echo $book->customer['name'] ?>

                </td>
                <td  style="text-align: center;">

                    <?php echo $book->pax ?>
                </td>
                <td  style="text-align: center;">

                    <?php echo $book->room->nameRoom ?>
                </td>
                <td  style="text-align: center;">
                    <?php
                        $start = Carbon::createFromFormat('Y-m-d',$book->start);
                        echo $start->formatLocalized('%d %b');
                    ?> -
                    <?php
                        $finish = Carbon::createFromFormat('Y-m-d',$book->finish);
                        echo $finish->formatLocalized('%d %b');
                    ?>
                </td>
                <td  style="text-align: center;">
                    <?php echo $book->nigths ?>
                </td>
                <td style="text-align: center;">
                    <?php if ($book->total_price > 0): ?>
                        <b><?php echo round($book->total_price) ?></b>
                    <?php else: ?>
                        <b>0</b>
                    <?php endif ?>
                    €
                </td>

                <td style="text-align: center;">
                    <?php if ( $book->getPayment(2) > 0): ?>
                        <?php echo round($book->getPayment(2)); ?>
                    <?php else: ?>
                        <b>0</b>
                    <?php endif ?>
                    €
                </td>
                <td class="text-center coste"  style="border-left: 1px solid black;">
                    <?php if ( $book->getPayment(3) > 0): ?>
                        <?php echo round($book->getPayment(3)); ?>
                    <?php else: ?>
                        <b>0</b>
                    <?php endif ?>
                    €
                </td>
                <td style="text-align: center;">
                    <?php if ( $book->getPayment(0) > 0): ?>
                        <?php echo round($book->getPayment(0)); ?>
                    <?php else: ?>
                        <b>0</b>
                    <?php endif ?>
                    €
                </td>
                <td class="text-center coste pagos" style="border-left: 1px solid black;">
                    <?php if ( $book->getPayment(1) > 0): ?>
                        <?php echo round($book->getPayment(1)); ?>
                    <?php else: ?>
                        <b>0</b>
                    <?php endif ?>
                    €
                </td>
                <td style="text-align: center;" >
                   <b>{{ $book->pending == 0 ? 0 : round($book->pending) }}€</b>
                </td>
                <td class="text-center beneficio bi" style="border-left: 1px solid black;">
                    <b>{{ $book->profit == 0 ? 0 : round($book->profit) }}€</b>
                </td>
                <td style="text-align: center;">
                    <?php if ( $book->inc_percent > 0): ?>
                        <?php echo round($book->inc_percent,0)." %" ?>
                    <?php else: ?>
                        0
                    <?php endif ?>

                </td>
                <td class="text-center coste bi " style="border-left: 1px solid black;">
                        {{ $book->costs == 0 ? 0 : round($book->costs) }}€
                </td>
                <td style="text-align: center;">
                    <?php if ( $book->cost_apto > 0): ?>
                        <?php echo round($book->cost_apto)?>
                    <?php else: ?>
                        0
                    <?php endif ?>
                    €
                </td>
                <td style="text-align: center;">
                    <?php if ( $book->cost_park > 0): ?>
                        <?php echo round($book->cost_park)?>
                    <?php else: ?>
                        0
                    <?php endif ?>
                    €
                </td>
                <td class="text-center coste"  style="border-left: 1px solid black;">
                    <?php if ( $book->cost_lujo > 0): ?>
                        <?php echo round($book->cost_lujo)?>
                    <?php else: ?>
                        0
                    <?php endif ?>
                    €
                </td>
                <td style="text-align: center;">
                    <?php if ( $book->cost_limp > 0): ?>
                        <?php echo round($book->cost_limp) ?>
                    <?php else: ?>
                        0
                    <?php endif ?>
                    €
                </td>
                <td class="text-center coste " style="border-left: 1px solid black;">
                    <?php if ( $book->PVPAgencia > 0): ?>
                        <?php echo round($book->PVPAgencia) ?>
                    <?php else: ?>
                        0
                    <?php endif ?>
                    €
                </td>
                <td style="text-align: center;">
                    <?php if ( $book->extraCost > 0): ?>
                        <?php echo round($book->extraCost) ?>€
                    <?php else: ?>
                        --
                    <?php endif ?>
                </td>
                <td class="text-center coste bf" style="border-left: 1px solid black;">
                    {{ $book->stripeCost == 0 ? 0 : round($book->stripeCost) }}€
                <td style="text-align: center;">
                    <?php echo round($book->getJorgeProfit()) ?>€
                </td>

                <td style="text-align: center;">
                    <?php echo round($book->getJaimeProfit()) ?>€
                </td>
            </tr>
        <?php endforeach ?>

    </tbody>
</table>
