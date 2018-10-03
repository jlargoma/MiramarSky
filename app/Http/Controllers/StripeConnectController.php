<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Stripe\Stripe as Stripe;
use Mail;
use DB;
use \Carbon\Carbon;
use App\Http\Requests;

class StripeConnectController extends Controller
{
	public static $stripe = [
		//"secret_key"      => "sk_test_o40xNAzPuB6sGDEY3rPQ2KUN",
		//"publishable_key" => "pk_test_YNbne14yyAOIrYJINoJHV3BQ"

		"secret_key"      => "sk_live_JKRWYAtvJ31tqwZyqNErMEap",
		"publishable_key" => "pk_live_wEAGo29RoqPrXWiw3iKQJtWk",
	];

	public function index(Request $request)
	{
		return view('backend.stripeConnect.index', [
			'owneds' => $this->loadQuery()
		]);
	}

	public function loadTransferForm(Request $request)
	{
		$owneds   = array();
		$rooms    = array();
		$arrayIds = json_decode($request->input(['owneds']));

		foreach ($arrayIds as $index => $arrayId)
		{
			$owneds[]             = \App\User::find($arrayId);
			$roomsOwned[$arrayId] = \App\Rooms::where('owned', $arrayId)->get();
		}

		return view('backend.stripeConnect.transferForm', [
			'owneds'     => $owneds,
			'roomsOwned' => $roomsOwned,
		]);
	}

	public function createAccountStripeConnect(Request $request)
	{
		$iban = $request->all()['iban'];
		$id   = (int) $request->all()['user'];

		$user       = \App\User::find($id);
		$user->iban = $iban;

		if (empty($user->account_stripe_connect))
		{
			\Stripe\Stripe::setApiKey(self::$stripe['secret_key']);

			$accountData                  = [
				"country"       => "ES",
				"type"          => "custom",
				"business_name" => $user->name,
				"email"         => $user->email,
			];
			$acct                         = \Stripe\Account::create($accountData);
			$user->account_stripe_connect = $acct->id;

		}

		$user->save();

	}

	public function loadTableOwneds()
	{
		$owneds = $this->loadQuery();
		return view('backend.stripeConnect._tableOwneds', [
			'owneds' => $owneds
		]);
	}

	public function loadQuery()
	{
		return  DB::select("SELECT u.id, u.name, u.iban, u.account_stripe_connect, r.id AS room_id
							FROM users u 
							INNER JOIN rooms r on r.owned = u.id AND r.state = 1 
							GROUP BY u.id
							ORDER BY r.order ASC");
	}


	/*
		'_token'       => string 'bXGgVhcD6qYXVfKPTs5KcSghNzf1MDCIrKw5im6a' (length=40)
		'fecha'        => string '01/10/2018' (length=10)
		'concept'      => string 'DEMO' (length=4)
		'type'         => string 'PAGO PROPIETARIO' (length=16)
		'type_payFor'  => string '1' (length=1)
		'importe'      => string '2500' (length=4)
		'type_payment' => string '3' (length=1)
		'comment'      => string 'sdfsdafasdfadsf' (length=15)
		'stringRooms'  => string '115,' (length=4)
		*/
	public function sendTransfers(Request $request)
	{
		\Stripe\Stripe::setApiKey(self::$stripe['secret_key']);
		foreach ($request->all()['users'] as $key => $dataUser)
		{
			$realPrice    = ($dataUser['import'] * 100);
			$user         = \App\User::find($dataUser['id']);
			$dataTransfer = [
				"amount"         => $realPrice,
				"currency"       => "eur",
				"destination"    => $user->account_stripe_connect,
				"transfer_group" => $dataUser['concept']
			];

			$transfer = \Stripe\Transfer::create($dataTransfer);

			if ($transfer->id)
			{
				$gasto              = new \App\Expenses();
				$gasto->concept     = $dataUser['concept'];
				$gasto->date        = Carbon::now()->format('Y-m-d');
				$gasto->import      = $dataUser['import'];
				$gasto->typePayment = 3;
				$gasto->type        = 'PAGO PROPIETARIO';
				$gasto->comment     = 'TRANSFERENCIA A ' . $user->name . " DESDE STRIPE CONNECT";
				$gasto->PayFor      = $dataUser['room_id'];

				$gasto->save();

				$data                = array();
				$data['concept']     = $dataUser['concept'];
				$data['typePayment'] = 2;
				$data['date']        = Carbon::now()->format('Y-m-d');
				$data['import']      = $dataUser['import'];
				$data['comment']     = 'TRANSFERENCIA A ' . $user->name . " DESDE STRIPE CONNECT";
				$data['type']        = 1;

				if (LiquidacionController::addBank($data))
					return redirect('admin/stripe-connect');
			}
		}

	}
}
