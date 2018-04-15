<?php   use \Carbon\Carbon;
        setlocale(LC_TIME, "ES"); 
        setlocale(LC_TIME, "es_ES"); 
?>
<table class="table table-hover">
    <thead>
    <tr>
        <th class ="text-center bg-complete text-white">
            F. Fact
        </th>
        <th class ="text-center bg-complete text-white" >
            # Fact
        </th>
        <th class ="text-center bg-complete text-white" >
            Apto
        </th>

        <th class ="text-center bg-complete text-white" >
            Cliente
        </th>
        <th class ="text-center bg-complete text-white" >
            DNI
        </th>
        <th class ="text-center bg-complete text-white" >
            Importe
        </th>
        <th class ="text-center bg-complete text-white" >
            Acciones
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($books as $key => $book): ?>
    <?php $num = $key + 1; ?>
    <tr>
        <td class="text-left font-s16" >
            <span class="hidden"><?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->format('U'); ?></span>
            <?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->formatLocalized('%d %B %Y'); ?>
        </td>
        <td class="text-center font-s16">
            <b>#<?php echo substr($book->room->nameRoom , 0,2)?>/<?php echo Carbon::CreateFromFormat('Y-m-d',$book->start)->format('Y'); ?>/<?php echo str_pad($num, 5, "0", STR_PAD_LEFT);  ?></b>
        </td>
        <td class="text-center font-s16">
            <b><?php echo $book->room->nameRoom ?></b>
        </td>

        <td class="text-center font-s16">
            <b><?php echo ucfirst($book->customer->name) ?></b>
        </td>
        <td class="text-center font-s16">
            <input type="text" class="form-control dni-customer" idCustomer="<?php echo $book->customer->id; ?>" value="<?php echo $book->customer->DNI ?>">
        </td>
        <td class="text-center font-s16">
            <b><?php echo number_format($book->total_price/2, 2, ',','.') ?>€</b>
        </td>
        <td class="text-center font-s16">
            <div class="btn-group">
                <a href="{{ url ('/admin/facturas/ver') }}/<?php echo base64_encode($book->id."-".$num) ?>" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i>
                </a>
                <a href="{{ url ('/admin/facturas/descargar') }}/<?php echo base64_encode($book->id."-".$num) ?>" class="btn btn-sm btn-success">    <i class="fa fa-download"></i>
                </a>
            </div>
        </td>
    </tr>
    <?php endforeach ?>
    </tbody>
</table>
{{ $books->links() }}