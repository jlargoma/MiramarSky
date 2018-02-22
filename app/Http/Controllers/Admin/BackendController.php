<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BackendController extends Controller
{
   	public function index()
    {
        return view('backend.home');
    }


    public function migrationCashBank()
    {
		foreach (\App\Paymentspro::All() as $key => $pay) {
			$gasto = new \App\Expenses();
			$gasto->concept = 'Pago propietario';
	        $gasto->date = $pay->datePayment;
	        $gasto->import = $pay->import;
	        $gasto->typePayment = $pay->type;
	        $gasto->type = 'PAGO PROPIETARIO';
	        $gasto->comment = $pay->comment;
	        $gasto->PayFor = $pay->room_id;
	        $gasto->save();
			if ($pay->type == 1 || $pay->type == 2) {
				$data['concept']     = $gasto->concept;
				$data['date']        = $gasto->date;
				$data['import']      = $gasto->import;
				$data['comment']     = $gasto->comment;
				$data['type']        = 1;

				$cashbox = new \App\Cashbox();
				$cashbox->concept = $data['concept'];
				$cashbox->date = \Carbon\Carbon::createFromFormat('Y-m-d', $data['date']);
				$cashbox->import = $data['import'];
				$cashbox->comment = $data['comment'];
				$cashbox->typePayment = ($pay->type - 1);
				$cashbox->type = $data['type'];
				if ($cashbox->save()) {
				    echo "Ok <br>";
				}	
			}
			if ($pay->type == 3 || $pay->type == 4) {

				$data['concept']     = $gasto->concept;
				$data['date']        = $gasto->date;
				$data['import']      = $gasto->import;
				$data['comment']     = $gasto->comment;
				$data['type']        = 1;

				$bank = new \App\Bank();
				$bank->concept = $data['concept'];
				$bank->date = \Carbon\Carbon::createFromFormat('Y-m-d', $data['date']);
				$bank->import = $data['import'];
				$bank->comment = $data['comment'];
				$bank->typePayment = ($pay->type - 1);
				$bank->type = $data['type'];
				if ($bank->save()) {
				    echo "Ok <br>";
				}
			}

			
		}

		// foreach (\App\Payments::All() as $key => $pay) {

			
		// 	if ($pay->type == 0 || $pay->type == 1 ) {
		// 		switch ($pay->type) {
		// 			case 0:
		// 				$data['concept'] ='COBRO METALICO JORGE '.$pay->book->customer->name;
						
		// 				break;
					
		// 			case 1:
		// 				$data['concept'] = 'COBRO METALICO JAIME '.$pay->book->customer->name;
		// 				break;
		// 		}
		// 		$data['typePayment'] = $pay->type;
		// 		$data['date']        = $pay->datePayment;
		// 		$data['import']      = $pay->import;
		// 		$data['comment']     = $pay->comment;
		// 		$data['type']        = 0;

		// 		$cashbox = new \App\Cashbox();
		// 		$cashbox->concept = $data['concept'];
		// 		$cashbox->date = \Carbon\Carbon::createFromFormat('Y-m-d', $data['date']);
		// 		$cashbox->import = $data['import'];
		// 		$cashbox->comment = $data['comment'];
		// 		$cashbox->typePayment = $data['typePayment'];
		// 		$cashbox->type = $data['type'];
		// 		if ($cashbox->save()) {
		// 		    echo "Ok <br>";
		// 		}
		// 	}
		// 	if ($pay->type == 2 || $pay->type == 3 ) {

		// 		switch ($pay->type) {
		// 			case 2:
		// 				$data['concept'] ='COBRO BANCO JORGE '.$pay->book->customer->name;
						
		// 				break;
					
		// 			case 3:
		// 				$data['concept'] = 'COBRO BANCO JAIME '.$pay->book->customer->name;
		// 				break;
		// 		}
		// 		$data['typePayment'] = $pay->type;
		// 		$data['date']        = $pay->datePayment;
		// 		$data['import']      = $pay->import;
		// 		$data['comment']     = $pay->comment;
		// 		$data['type']        = 0;

		// 		$bank = new \App\Bank();
		// 		$bank->concept = $data['concept'];
		// 		$bank->date = \Carbon\Carbon::createFromFormat('Y-m-d', $data['date']);
		// 		$bank->import = $data['import'];
		// 		$bank->comment = $data['comment'];
		// 		$bank->typePayment = $data['typePayment'];
		// 		$bank->type = $data['type'];
		// 		if ($bank->save()) {
		// 		    echo "Ok <br>";
		// 		}

		// 	}

		// }


		// foreach (\App\Expenses::All() as $key => $gasto) {
			
		// 	if ($gasto->typePayment == 1 || $gasto->typePayment == 2 ) {

		// 		switch ($gasto->type) {
		// 			case 1:
		// 				$data['typePayment'] = 1;
						
		// 				break;
					
		// 			case 2:
		// 				$data['typePayment'] = 0;
		// 				break;
		// 		}

		// 		$data['concept']     = $gasto->concept;
		// 		$data['date']        = $gasto->date;
		// 		$data['import']      = $gasto->import;
		// 		$data['comment']     = $gasto->comment;
		// 		$data['type']        = 1;

		// 		$cashbox = new \App\Cashbox();
		// 		$cashbox->concept = $data['concept'];
		// 		$cashbox->date = \Carbon\Carbon::createFromFormat('Y-m-d', $data['date']);
		// 		$cashbox->import = $data['import'];
		// 		$cashbox->comment = $data['comment'];
		// 		$cashbox->typePayment = $data['typePayment'];
		// 		$cashbox->type = $data['type'];
		// 		if ($cashbox->save()) {
		// 		    echo "Ok <br>";
		// 		}
		// 	}
		// 	if ($gasto->typePayment == 0 || $gasto->typePayment == 3 ) {

		// 		switch ($gasto->type) {
		// 			case 0:
		// 				$data['typePayment'] = 3;
						
		// 				break;
					
		// 			case 3:
		// 				$data['typePayment'] = 2;
		// 				break;
		// 		}

		// 		$data['concept']     = $gasto->concept;
		// 		$data['date']        = $gasto->date;
		// 		$data['import']      = $gasto->import;
		// 		$data['comment']     = $gasto->comment;
		// 		$data['type']        = 1;

		// 		$bank = new \App\Bank();
		// 		$bank->concept = $data['concept'];
		// 		$bank->date = \Carbon\Carbon::createFromFormat('Y-m-d', $data['date']);
		// 		$bank->import = $data['import'];
		// 		$bank->comment = $data['comment'];
		// 		$bank->typePayment = $data['typePayment'];
		// 		$bank->type = $data['type'];
		// 		if ($bank->save()) {
		// 		    echo "Ok <br>";
		// 		}
		// 	}
		// }
    }

}
