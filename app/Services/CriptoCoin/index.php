<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
$urlRetur = 'http://dev-miramar.virtual/payByCripto';
$success_url = 'http://dev-miramar.virtual/payByCripto?success=1';
$cancel_url = 'http://dev-miramar.virtual/payByCripto?error=1';
$ipn_url = 'http://dev-miramar.virtual/payByCripto?ipn=1';



$merchant_id = 'ea84a699e42f3ce88d72e4b89058e83a';

/**
 * 
 * 	Nombre de la Llave: Llave API Sin Nombre
Llave Pública: f37c94771cf714281c4bd4963341c246e1a689c2bf3e98db6055eefd5844e38a
Llave Privada: 9Ff6d29dB4635B72a36D677fAFBb18c48adDFB734611dE29896B3d4e22e5da56
 * 
 */
$ipn_secret = '9Ff6d29dB4635B72a36D677fAFBb18c48adDFB734611dE29896B3d4e22e5da56';


$first_name = 'first_name';
$last_name = 'last_name';
$email = 'pingodevweb@gmail.com';

$title = '$get_option( title)';
$description = '$get_option( description)';
$send_shipping = false;
$debug_email = '$get_option( debug_email)';
$allow_zero_confirm = false;
$form_submission_method = false;
$invoice_prefix = 'MIRAMARSKI';
$simple_total = false;

// CoinPayments.net Args
$coinpayments_args = array(
    'cmd' => '_pay_auto',
    'merchant' => $merchant_id,
    'allow_extra' => 0,
    'currency' => 'USD',
    'reset' => 1,
    'success_url' => $success_url,
    'cancel_url' => $cancel_url,
    'invoice' => "MIRAMARSKI-RVA-1565",
    'custom' => "1565",
    'ipn_url' => $ipn_url,
    'first_name' => $first_name,
    'last_name' => $last_name,
    'email' => $email,
);

$coinpayments_args['want_shipping'] = 0;
$coinpayments_args['item_name'] = "RVA-1565";
$coinpayments_args['quantity'] = 1;
$coinpayments_args['amountf'] = 123;
$coinpayments_args['shippingf'] = 0;
$coinpayments_args['taxf'] = 0;

$coinpayments_adr = "https://www.coinpayments.net/index.php?";
$coinpayments_adr .= http_build_query($coinpayments_args, '', '&');



echo urldecode($coinpayments_adr);
die;
