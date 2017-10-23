<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;
use Mail;

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
                    'rooms' => \App\Rooms::where('state',1)->orderBy('order','ASC')->get(),
                    'sizes'  => \App\SizeRooms::all(),
                    'types'  => \App\TypeApto::all(),
                    'tipos'  => \App\TypeApto::all(),
                    'owners' => \App\User::all(),
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

    public function uploadFile(Request $request,$id)
    {   
        echo "<pre>";
        $file = ($_FILES);

        $room = \App\Rooms::where('nameRoom',$id)->first();

        $directory =public_path()."/img/miramarski/apartamentos/".$room->nameRoom;

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // echo $storage_path . basename( $_FILES['uploadedfile']['name']);
        $directory = $directory."/" . basename( $_FILES['uploadedfile']['name']); 

        if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $directory))
        {
            return "imagen subida";
        } else{
            return "imagen no subida";
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

        Mail::send('backend.emails.accesoPropietario',['data' => $request->input()], function ($message) use ($user) {
            $message->from('reservas@apartamentosierranevada.net');
            $message->attach(public_path("contrato-comercializacion-17-18.pdf"));
            $message->to($user->email);
            $message->subject('Datos de acceso para ApartamentoSierraNevada');
        });

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


}
