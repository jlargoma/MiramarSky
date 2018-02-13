<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;
use Mail;
use File;
use Intervention\Image\ImageManagerStatic as Image;

class RoomsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend/rooms/index',[
                    'rooms'     => \App\Rooms::where('state',1)->orderBy('order','ASC')->get(),
                    'roomsdesc' => \App\Rooms::where('state',0)->orderBy('order','ASC')->get(),
                    'sizes'     => \App\SizeRooms::all(),
                    'types'     => \App\TypeApto::all(),
                    'tipos'     => \App\TypeApto::all(),
                    'owners'    => \App\User::all(),
                    'typesApto' => \App\TypeApto::all(),
                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function create(Request $request)
    {
        $room = new \App\Rooms();


        
        if($request->input('luxury') == "on"){
            $luxury = 1;
        }
        else{
            $luxury = 0;
        }
        $room->name = $request->input('name');
        $room->nameRoom = $request->input('nameRoom');
        $room->minOcu = $request->input('minOcu');
        $room->maxOcu = $request->input('maxOcu');
        $room->owned = $request->input('owner');
        $room->typeApto = $request->input('type');
        $room->sizeApto = $request->input('sizeRoom');
        $room->profit_percent = 0;
        $room->luxury = $luxury;
        $room->order = 99;
        $room->state = 1;

        // $directory =public_path()."/img/miramarski/galerias/".$room->nameRoom;

        
        // if (!file_exists($directory)) {
        //     mkdir($directory, 0777, true);
        // }

        if ($room->save()) {
            return redirect()->action('RoomsController@index');
        }
    }

    public function createType(Request $request)
    {
       $existTypeRoom = \App\TypeApto::where('name',$request->input('name'))->count();
       if ($existTypeRoom == 0) {
           $roomType = new \App\TypeApto();

           $roomType->name = $request->input('name');
           
           if ($roomType->save()) {
               return redirect()->action('RoomsController@index');
           }
       }else{
           echo "Ya existe este tipo de apartamento";
       }
    }

    public function createSize(Request $request)
    {
        $existTypeRoom = \App\SizeRooms::where('name',$request->input('name'))->count();
        if ($existTypeRoom == 0) {
            $roomType = new \App\SizeRooms();

            $roomType->name = $request->input('name');
            
            if ($roomType->save()) {
                return redirect()->action('RoomsController@index');
            }
        }else{
            echo "Ya existe este tipo de apartamento";
        }
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id                   = $request->input('id');
        $roomUpdate          = \App\Rooms::find($id);

        $roomUpdate->luxury = $request->input('luxury');
        $roomUpdate->minOcu = $request->input('minOcu');
        $roomUpdate->maxOcu = $request->input('maxOcu');
        

        if ($roomUpdate->save()) {
            echo "Cambiada!!";
        }
    }

    public function updateType(Request $request)
    {
        $id                   = $request->id;
        $roomUpdate          = \App\Rooms::find($id);


        $roomUpdate->typeApto = $request->tipo;
        

        if ($roomUpdate->save()) {
            echo "Cambiada!!";
        }
    }

    public function updateOwned(Request $request)
    {
        $id                   = $request->id;
        $roomUpdate           = \App\Rooms::find($id);
        $roomUpdate->owned    = $request->owned;
        

        if ($roomUpdate->save()) {
            echo "Cambiada!!";
        }
    }

    // Funcion para cambiar el nombre del apartamento
    public function updateName(Request $request)
    {
        $id                   = $request->id;
        $roomUpdate          = \App\Rooms::find($id);
        $roomUpdate->name = $request->name;
        if ($roomUpdate->save()) {
            }
    }

    // Funcion para cambiar el nombre del apartamento
    public function updateNameRoom(Request $request)
    {
        $id                   = $request->id;
        $roomUpdate          = \App\Rooms::find($id);
        $roomUpdate->nameRoom = $request->nameRoom;
        if ($roomUpdate->save()) {
            }
    }

    // Funcion para cambiar el orden
    public function updateOrder(Request $request)
    {
        $id                   = $request->id;
        $roomUpdate          = \App\Rooms::find($id);
        $roomUpdate->order = $request->orden;
        if ($roomUpdate->save()) {
            }
    }

    // Funcion para cambiar el Tamaño
    public function updateSize(Request $request)
    {
        $id                   = $request->id;
        $roomUpdate          = \App\Rooms::find($id);
        $roomUpdate->sizeApto = $request->size;
        if ($roomUpdate->save()) {
            }
    }

    // Funcion para cambiar el parking
    public function updateParking(Request $request)
    {
        $id                   = $request->id;
        $roomUpdate          = \App\Rooms::find($id);
        $roomUpdate->parking = $request->parking;
        if ($roomUpdate->save()) {
            }
    }

    // Funcion para cambiar la Taquilla
    public function updateTaquilla(Request $request)
    {
        $id                   = $request->id;
        $roomUpdate          = \App\Rooms::find($id);
        $roomUpdate->locker = $request->taquilla;
        if ($roomUpdate->save()) {
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function state(Request $request)
    {
        $room = \App\Rooms::find($request->id);
        $book = \App\Book::where('room_id','=',$request->id)->where('start','>','2017-09-01')->get();

        if (count($book) > 0) {
            return 0;
        }else{
            $room->state = $request->state;
               if ( $room->save() ) {
                    return 1;
               }
        }
    }
    
    public static function getPaxPerRooms($room)
    {
        $room = \App\Rooms::where('id', $room)->first();
        
        return $room->minOcu;    
    }

    public static function getLuxuryPerRooms($room)
    {
        
        $room = \App\Rooms::where('id', $room)->first();
        // echo "$room->luxury";
        return $room->luxury;    
    }

    public function photo($id)
    {   
        $room = \App\Rooms::where('nameRoom',$id)->first();
        $directory =public_path()."/img/miramarski/apartamentos/".$room->nameRoom;
        
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        $directorio=dir($directory);

        return view('backend/rooms/photo',[
                                            'directory' => $directorio,
                                            'room' => $room,
                                        ]);
    }

    public function uploadFile(Request $request,$id)
    {   

        $file = ($_FILES);

        $room = \App\Rooms::where('nameRoom',$id)->first();

        $directory =public_path()."/img/miramarski/apartamentos/".$room->nameRoom;


        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        // RECORREMOS LOS FICHEROS
        for($i=0; $i<count($_FILES['uploadedfile']['name']); $i++) {
          //Obtenemos la ruta temporal del fichero
          $fichTemporal = $_FILES['uploadedfile']['tmp_name'][$i];
         
          //Si tenemos fichero procedemos
          if ($fichTemporal != ""){
            //Definimos una ruta definitiva para guardarlo
            $destino = $directory."/". $_FILES['uploadedfile']['name'][$i];
         
            //Movemos a la ruta final
            if(move_uploaded_file($fichTemporal, $destino)) {
               //imprimimos el nombre del archivo subido
               printf("Se ha subido el fichero %s.",$_FILES['uploadedfile']['name'][$i]);
               $status = true;
            }else{
                $statu = false;
            }
          }
        }
        if($status)
        {
            return redirect()->action('RoomsController@index');
        } else{
            return redirect()->action('RoomsController@index');
        }

    }

    public function deletePhoto(Request $request, $id)
    {

        $archivo = public_path()."/img/miramarski/apartamentos/".$request->input('apto')."/".$id;
        
        if (unlink($archivo)) {
            return "OK";
        }else{
            return "no se puede eliminar";
        }
    }

    public function email($id){
        
        $user = \App\User::find($id);
        $rooms = \App\Rooms::where('owned', $id)->get();
        return view('backend/emails/_emailingToOwned',[ 'user' => $user, 'rooms' => $rooms,]);

    }

    public function sendEmailToOwned(Request $request)
    {
        $user = \App\User::find($request->input('user'));
        if ($request->input('attachment') == 1) {
            Mail::send('backend.emails.accesoPropietario',['data' => $request->input()], function ($message) use ($user) {
                $message->from('reservas@apartamentosierranevada.net');
                $message->attach(public_path("contrato-comercializacion-17-18.pdf"));
                $message->to($user->email);
                $message->subject('Datos de acceso para ApartamentoSierraNevada');
            });
        }else{
            Mail::send('backend.emails.accesoPropietario',['data' => $request->input()], function ($message) use ($user) {
                $message->from('reservas@apartamentosierranevada.net');
                $message->to($user->email);
                $message->subject('Datos de acceso para ApartamentoSierraNevada');
            });
        }


        

        return redirect()->back();
    }

    public function assingToBooking(Request $request)
    {
        $room = \App\Rooms::find($request->id);

        if ($request->assing == 1) {

            if ( $room->isAssingToBooking() ) {
                return "Este apto. ya esta cedido a booking";
            } else {

                $date = Carbon::now();

                if ($date->copy()->format('n') >= 9) {
                    $start = new Carbon('first day of September '.$date->copy()->format('Y'));
                }else{
                    $start = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));

                }


                $bookToAssign = new \App\Book();

                $bookToAssign->user_id       = 39;
                $bookToAssign->customer_id   = 1818;
                $bookToAssign->room_id       = $room->id;
                $bookToAssign->start         = $start->format('Y-m-d');
                $bookToAssign->finish        = $start->copy()->addMonths(9)->format('Y-m-d');
                $bookToAssign->comment       = "";
                $bookToAssign->book_comments = "";
                $bookToAssign->type_book     = 9;
                $bookToAssign->pax           = 1;
                $bookToAssign->nigths        = 121;
                $bookToAssign->agency        = 0;
                $bookToAssign->PVPAgencia    = 0;
                $bookToAssign->sup_limp      = 0;
                $bookToAssign->sup_park      = 0;
                $bookToAssign->type_park     = 0;
                $bookToAssign->cost_park     = 0;
                $bookToAssign->type_luxury   = 2;
                $bookToAssign->sup_lujo      = 0;
                $bookToAssign->cost_lujo     = 0;
                $bookToAssign->cost_apto     = 0;
                $bookToAssign->cost_total    = 0;
                $bookToAssign->total_price   = 0;
                $bookToAssign->real_price    = 0;
                $bookToAssign->total_ben     = 0;
                $bookToAssign->extraPrice    = 0;
                $bookToAssign->extraCost     = 0;
                //Porcentaje de beneficio
                $bookToAssign->inc_percent   = 0;
                $bookToAssign->ben_jorge     = 0;
                $bookToAssign->ben_jaime     = 0;

                if ($bookToAssign->save()) {
                    echo "Assignado a booking";
                } else{
                    echo "Error al crear el bookeo";

                }
            }
            
            
        } else {

            $books = \App\Book::where('room_id', $request->id)->where('type_book', 9)->get();
            foreach ($books as $key => $book) {
                $book->delete();
            }

            $redo = \App\Book::where('room_id', $request->id)->where('type_book', 9)->get();
            if( count($redo) == 0)
                echo "Bloqueo borrado!";
            
        }
        
    }

    public function percentApto(Request $request)
    {
        $typesApto = \App\TypeApto::all();

        return view('backend/rooms/typesApto',['typesApto' => $typesApto]);
    }

    public function updatePercent(Request $request)
    {
        $typeApto = \App\TypeApto::find($request->input('id'));
        $tipo     = $request->input('tipo');
        $percent  = $request->input('percent');

        if (preg_match('/jorge/i', $tipo)) {
            $typeApto->PercentJorge = $percent;
            if ($typeApto->save()) {
                return "ok";
            }
        }else{
            $typeApto->PercentJaime = $percent;
            if ($typeApto->save()) {
                return "ok";
            }
        }

    }

    public function saveupdate(Request $request)
    {
        
        // echo "<pre>";
        // print_r($request->input());
        // die();

        $room           = \App\Rooms::find($request->input('id'));
        $room->name     = $request->input('name');
        $room->nameRoom = $request->input('nameRoom');
        $room->minOcu   = $request->input('minOcu');
        $room->maxOcu   = $request->input('maxOcu');
        $room->owned    = $request->input('owned');
        $room->typeApto = $request->input('type');
        $room->sizeApto = $request->input('sizeApto');
        $room->parking  = $request->input('parking');
        $room->locker   = $request->input('locker');
        $room->profit_percent   = $request->input('profit_percent');

        if ($room->save()) {
            return redirect()->action('RoomsController@index');
        }
    }

    public function getUpdateForm(Request $request)
    {
        return view('backend/rooms/_updateFormRoom',[ 'room' => \App\Rooms::find($request->id) ]);
    }

    

    public function searchByName(Request $request)
    {
        $rooms     = \App\Rooms::where('state',1)->where('name', 'LIKE', '%'.$request->searchString.'%')->orderBy('order','ASC')->get();
        $roomsdesc = \App\Rooms::where('state',0)->where('name', 'LIKE', '%'.$request->searchString.'%')->orderBy('order','ASC')->get();

        return view('backend/rooms/_tableRooms',[ 
                                                    'rooms' => $rooms,
                                                    'roomsdesc' => $roomsdesc,
                                                    'show'      => 1,
                                                ]);
    }


    public function getImagesRoom(Request $request, $id="")
    {
        if ($id != '') {
            $room = \App\Rooms::find($id);
            $path = public_path().'/img/miramarski/apartamentos/'.$room->nameRoom.'/thumbnails/';

            if (File::exists($path)){
                $images = File::allFiles($path);
                foreach ($images as $key => $slide) {
                    $arraySlides[] = $slide->getFilename();
                }
                natcasesort($arraySlides);
                $slides = array();
                foreach ($arraySlides as $key => $sl) {
                 $slides[] = '/img/miramarski/apartamentos/'.$room->nameRoom.'/thumbnails/'.$sl;      
                }
                return view('backend/rooms/_imagesByRoom', ['images' => $slides, 'room' => $room]);

            }else{
                return '<h2 class="text-center">NO HAY IMAGENES PARA ESTE APTO.</h2>';
            }
        } else {
            
            // $rooms = \App\Rooms::where('state', 1)->get();
            // foreach ($rooms as $key => $room) {
            //     $counter = 0;
            //     $path = public_path().'/img/miramarski/apartamentos/'.$room->nameRoom.'/thumbnails/';

            //     if (File::exists($path)){

            //         $images = File::allFiles($path);

            //         foreach ($images as $key => $image) {
            //             File::move($path.$image->getFilename(), $path.str_replace(' ', '_', $image->getFilename()));


            //         }
                    
            //     }
            // }
            


       }
        
       
    }

    public function sendImagesRoomEmail(Request $request)
    {
        
        $email = $request->email;
        $room = \App\Rooms::find($request->roomId);
        $path = public_path().'/img/miramarski/apartamentos/'.$room->nameRoom.'/thumbnails/';

        if (File::exists($path)){
            $images = File::allFiles($path);

            Mail::send('backend.emails._imagesRoomEmail',['room' => $room], function ($message) use ($email, $images, $room, $path) {
                $luxury = ($room->luxury == 1)? "Lujo" : "Estandar";
                $message->from('reservas@apartamentosierranevada.net');

                foreach ($images as $key => $image):
                    $message->attach($path.$image->getFilename());
                endforeach;
                

                $message->to($email);
                $message->subject('Imagenes del apartamento '.$room->sizeRooms->name.' // '. $luxury);
            });

        }

    }

}
