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
        return view('backend/rooms/rooms',[
                    'rooms' => \App\Rooms::all(),
                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function newRoom()
    {
        return view('backend/rooms/new-room',[
                                                'users' => \App\User::all(),
                                                'sizes' =>\App\SizeRooms::all(),
                                                'types'  => \App\TypeRooms::all(),
                                            ]);
    }
    public function newTypeRoom()
    {
        return view('backend/rooms/new-type-room', [
                                                    'types'  => \App\TypeRooms::all(),
                                                    ]);
    }
    public function newSizeRoom()
    {
        return view('backend/rooms/new-size-room', [
                                                    'sizes' =>\App\SizeRooms::all(),
                                                    ]);
    }

    public function create(Request $request)
    {
        $room = new \App\Rooms();

        $room->name = $request->input('name');
        $room->nameRoom = $request->input('nameRoom');
        $room->owned = $request->input('type');
        $room->user_id = $request->input('propietario');
        $room->typeApto = $request->input('lujo');
        $room->sizeRoom = $request->input('size');
        
        if ($room->save()) {
            return redirect()->action('RoomsController@index');
        }
    }

    public function createType(Request $request)
    {
        $existTypeRoom = \App\TypeRooms::where('name',$request->input('name'))->count();
        if ($existTypeRoom == 0) {
            $roomType = new \App\TypeRooms();

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
        $existRoomSize = \App\SizeRooms::where('name',$request->input('name'))->count();
        if ($existRoomSize == 0) {
            $roomSize = new \App\SizeRooms();

            $roomSize->name = $request->input('name');
            $roomSize->maxOcu = $request->input('maxOcu');
            $roomSize->minOcu = $request->input('minOcu');
            
            if ($roomSize->save()) {
                return redirect()->action('RoomsController@index');
            }
        }else{
            echo "Ya existe este tamaño";
        }
        
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $roomUpdate->name = $request->input('name');
        // $roomUpdate->nameRoom = $request->input('nameRoom');
        // $roomUpdate->sizeRoom = $request->input('type');
        // $roomUpdate->user_id = $request->input('user');
        // $roomUpdate->typeApto = $request->input('lujo');
        

        if ($roomUpdate->save()) {
            echo "Cambiada!!";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $room = \App\Rooms::find($id);
        if ( $room->delete() ) {
            return redirect()->action('RoomsController@index');
        }
    }
}
