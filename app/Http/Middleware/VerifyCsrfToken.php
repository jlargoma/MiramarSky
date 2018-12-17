<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'reservas/stripe/payment',
        '/getDiffIndays',
        '/admin/reservas/stripe/paymentsBooking',
        '/solicitudForfait',
        '/admin/stripe-connect/load-transfer-form',
        '/admin/stripe-connect/create-account-stripe-connect',
        '/ajax/requestPrice',
        '/ajax/forfaits/updateRequestStatus',
        '/ajax/forfaits/updateRequestPAN',
        '/ajax/forfaits/updateRequestComments',
        '/ajax/forfaits/updateCommissions',
        '/ajax/forfaits/updatePayments',
        '/ajax/forfaits/requestPriceForfaits',
        '/ajax/reservas/getBookData'
    ];
}
