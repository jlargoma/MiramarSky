<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

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
                    'owners' => \App\User::whereIn('role',['Admin','Propietario'])->get(),
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
            $room->owned = $request->input('owner');
            $room->typeApto = $request->input('type');
            $room->sizeApto = $request->input('sizeRoom');
            $room->luxury = $luxury;
            $room->order = 99;
            $room->state = 1;

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

    // Funcion para cambiar el nombre del apartamento
        public function updateName(Request $request)
            {
                $id                   = $request->id;
                $roomUpdate          = \App\Rooms::find($id);
                $roomUpdate->nameRoom = $request->name;
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

    public function uploadFile(Request $request)
        {   
            echo "llega";
            die();

            $room = \App\Rooms::find($request->id);

            $directory =public_path()."/img/miramarski/galerias/".$room->nameRoom;

            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $directory =public_path()."/img/miramarski/galerias/".$room->nameRoom."/";
            // echo $storage_path . basename( $_FILES['uploadedfile']['name']);
            $directory = $directory . basename( $_FILES[$request->type]['name']); 
            if(move_uploaded_file($request->type, $directory)) {
                echo "subido";
                return redirect()->action('RoomsController@index');
            } else{
                echo "no subido";
                return redirect()->action('RoomsController@index');
            }
        }

}
