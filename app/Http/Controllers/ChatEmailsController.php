<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\IMAP\Client;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use App\Services\POP3Service;

class ChatEmailsController extends Controller
{
    public function index() {
    
  $oObtieneMails= new POP3Service();
  $inbox =  $oObtieneMails->pop3_login(
          'mail.riadpuertasdelalbaicin.com',
          110,
          'reservas@riadpuertasdelalbaicin.com',
          'exitO@007'
          );

  $emails = $oObtieneMails->getMails($inbox,"reservas@riadpuertasdelalbaicin.com",'from');
 
 $result = $oObtieneMails->getMailsContent($inbox, $emails);
 var_dump($result); die;
 dd($result);
//ejecutamos el metodo
//$oObtieneMails->obtenerAsuntosDelMails($inbox);
    
  }
}
