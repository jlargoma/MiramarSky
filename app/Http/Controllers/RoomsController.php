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
                                            ]);
    }

    public function create(Request $request)
    {
        $room = new \App\Rooms();

        $room->name = $request->input('name');
        $room->nameRoom = $request->input('nameRoom');
        $room->user_id = $request->input('propietario');
        $room->typeApto = $request->input('lujo');
        $room->sizeRoom = $request->input('size');
        
        if ($room->save()) {
            return redirect()->action('RoomsController@index');
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
        $user = \App\User::find($id);
        if ( $user->delete() ) {
            return redirect()->action('RoomsController@index');
        }
    }
}
