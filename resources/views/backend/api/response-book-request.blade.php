<div class="col-md-12">
	<?php foreach ($rooms as $key => $room): ?>
    <div class="col-md-4">
        <h2 class="text-left">
            {{ $room->name }}
        </h2>
        <p class="text-justify">
            {{ $room->description }}
        </p>
		<?php $nights = $start->copy()->diffInDays($finish); ?>
		<?php $luxury = ($room->luxury != 0) ? $room->luxury : 2; ?>
		<?php $priceBook = \App\Http\Controllers\BookController::getPriceBook($start->copy()->format('d/m/Y'), $finish->copy()->format('d/m/Y'), $pax, $room->id); ?>
		<?php $pricePark = \App\Http\Controllers\BookController::getPricePark(1, $nights); ?>
		<?php $priceLuxury = \App\Http\Controllers\BookController::getPriceLujo($luxury); ?>
        <div class="col-md-12">
            <p class="text-left">
                <b><span class="font-s30 font-w800">Datos de la reserva:</span></b><br>
                Estancia: <b>{{ $nights }} @if($nights == 1)Noche @else Noches @endif </b><br>
                Reserva: <b>{{ $priceBook }}€</b> <br>
                Parking: <b>{{ $pricePark }}€</b> <br>
                Lujo: <b>{{ $priceLuxury }}€</b> <br>
            </p>

            <h3 class="text-center">
                Total de la reserva: <b>{{ $priceBook + $pricePark + $priceLuxury }}€</b>
            </h3>
            <div class="col-md-12 text-center">
                @if($instantPayment)
                    <form action="{{ route('book.create') }}">

                    </form>
                @else
                    <button class="btn btn-primary font-w300">Solicitar Reserva</button>
                @endif
            </div>
        </div>
    </div>
	<?php endforeach; ?>
</div>