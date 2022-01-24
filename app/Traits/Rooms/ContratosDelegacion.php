<?php

namespace App\Traits\Rooms;

use App\Settings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait ContratosDelegacion
{
/*
 * -----------------------------------------------------------
 *  CONTRATOS
 * -----------------------------------------------------------
 */
  
  
  /*
   * If the contract is signed, show the PDF
   */
  function getContratoDelegacion($id,$pdf=false,$cedeToName=null,$cedeToDni=null){
    $oContr = \App\RoomsContracts::find($id);
    if (!$oContr) return ['error'=>'Contrato no encontrado'];
    $oRoom = \App\Rooms::find($oContr->room_id);
    
    // Controls -------------------------------------------------------
    $oUsr = Auth::user();
    $oUsrRoom = null;
    if ($oRoom){
      if($oRoom->id != $oContr->room_id) 
         return ['error'=>'Contrato no encontrado'];
      
      if ($oRoom->owned != $oUsr->id && $oUsr->role != 'admin')
        return ['error'=>'Contrato no encontrado'];
      
      $oUsrRoom = \App\User::find($oRoom->owned);
    } else {
      if ($oUsr->role != 'admin') 
        return ['error'=>'Contrato no encontrado'];
    }
    // Controls -------------------------------------------------------   
  
    $content = $oContr->content;
    $oYear = \App\Years::find($oContr->year_id);
    
    // Already Signed  -------------------------------------------
    $fileName = $oContr->file;
    $path = storage_path('app/'.$fileName);
    if (!$pdf && $fileName && File::exists($path)){
      return[
          'id' => $oContr->id,
          'path' => $path,
          'file_name'=>'DELEGACIÓN DE VOTO PARA JUNTA DE VECINOS MIRAMAR SKI.pdf',
          'sign' => true,
      ];
    }
    // Already Signed  -------------------------------------------
   
    $date= '';
    $aux = explode(' ',$oContr->updated_at);
    $aux = explode('-',$aux[0]);
    if (is_array($aux) && count($aux)==3){
      $date= ', '.$aux[2].' de '.getMonthsSpanish($aux[1],false).' '.$aux[0];
    }
    
    
    return[
        'id' => $oContr->id,
        'text' => $oContr->getText($oRoom,$oUsrRoom,null,null,$cedeToName,$cedeToDni),
        'date' => $date,
        'room' => $oRoom,
        'sign' => false,
    ];
  }
  
  function seeContratoDelegacion($id){
    
    $data = $this->getContratoDelegacion($id);
    if (isset($data['error'])){
      return redirect('404')->withErrors([$data['error']]);
    }
    if ($data['sign']){
      return response()->file($data['path'], [
        'Content-Disposition' => str_replace('%name', $data['file_name'], "inline; filename=\"%name\"; filename*=utf-8''%name"),
        'Content-Type'        => 'application/pdf'
      ]);
    }
   return view('backend.owned.contratoDelegacion',$data);
  }
  
  
  

  function downlContratoDelegacion($id) {
    $data = $this->getContratoDelegacion($id,true);
    if ($data['sign']){
      return response()->download($data['path'], 'contrato-MiramarSKY.pdf', [], 'inline');
    } 
    
    return back()->withErrors(['contrato no encontrado']);
        
  }
  

  
  /*
   * Set Sign, create the PDF and send mail
   */
  function setSignContratoDelegacion(Request $req){
    $contrID = $req->input('ID');
    
    $oUsr = Auth::user();
    $oContr = \App\RoomsContracts::find($contrID);
    if (!$oContr) return redirect('404')->withErrors(['Contrato no encontrado']);
    
    $cedeToName = $req->input('cedeToName');
    $cedeToDni = $req->input('cedeToDni');
    if (trim($cedeToName) == '' || trim($cedeToDni) == ''){
      return  redirect()->back()->withErrors(['El nombre y el dni de su representante es obligatorio']);
    }
    
   
    $sign = $req->input('sign');
    $encoded_image = explode(",", $sign)[1];
    $decoded_image = base64_decode($encoded_image);

    //Data ---------------------------------------------------
    $data = $this->getContratoDelegacion($contrID,true,$cedeToName,$cedeToDni);
    if (isset($data['error'])){
      return redirect('404')->withErrors([$data['error']]);
    }
    $text = $data['text'];
    
    //Signs -------------------------------------------
    $data['signFile'] = $encoded_image;
    $path = storage_path('/app/signs/contratos.png');
    $file = File::get($path);
    $data['signAdmin'] = base64_encode($file);
    
    //PDF -------------------------------------------
    $pdf = \App::make('dompdf.wrapper');
    $pdf->getDomPDF()->set_option("enable_php", true);
    $pdf->loadView('backend.owned.contratoDelegacionDownl',$data);
    $output = $pdf->output();
//        return $pdf->download('invoice.pdf');
//    return $pdf->stream();
        
    //save document
    $fileName = 'contracts/' .$contrID.'-'. $oUsr->id .'-'.time().'.pdf';
    $path = storage_path('/app/' . $fileName);
    
    $oContr->file = $fileName;
    $oContr->save();
    $storage = \Illuminate\Support\Facades\Storage::disk('local');
    $storage->put($fileName, $output);
    
    //---------------------------------------------------
    // Send Mail
    $oRoom = \App\Rooms::where('id',$oContr->room_id)->with('user')->first();
    if (!$oRoom) return 'Apto no encontrado';
    
    
    $oYear = \App\Years::find($oContr->year_id);
    if (!$oYear) return 'Temporada no encontrada';
    $seasson = $oYear->year.' - '.($oYear->year+1);
    
    $subject = 'Contrato de DELEGACIÓN DE VOTO PARA JUNTA DE VECINOS MIRAMAR SKI';
    $mailContent = 'Hola '.$oRoom->user->name.', <br/><br/>';
    $mailContent .= '<p>Gracias por firmar su contrato para la delegación de representación del apartamento <b>'.$oRoom->nameRoom.'</b> para la próxima Junta General Ordinaria de la Comunidad.</b></p>';
    $mailContent .= '<p>Le adjuntamos el documento firmado.</p>';
    $mailContent .= '<br/><br/><br/><p>Muchas Gracias.!</p>';
    $email = $oRoom->user->email;
    
    try{
      $fileName = $subject;
      Mail::send('backend.emails.base', [
            'mailContent' => $mailContent,
            'title'       => $subject
        ], function ($message) use ($subject,$email,$path,$fileName) {
            $message->from(env('MAIL_FROM'),env('MAIL_FROM_NAME'));
            $message->subject($subject);
            $message->to($email);
            $message->attach( $path, array(
                            'as' => $fileName.'.pdf', 
                            'mime' => 'application/pdf'));
        });
        
      return back()->with(['success' => 'Firma Guardada']);
    } catch (\Exception $e){
      return $e->getMessage();
    }
    //---------------------------------------------------
  }
}
