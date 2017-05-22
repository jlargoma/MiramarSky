<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend/users/index',  [
                                                'users' => \App\User::all(),
                                            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        $user = new \App\User();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
        $user->password = $request->input('password');
        
        if ($user->save()) {
            return redirect()->action('UsersController@index');
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

    public function update($id)
    {
        $user = \App\User::find($id);

        return view('backend/users/_form',  [
                                                'user' => $user
                                            ]);
    }

    public function saveUpdate(Request $request)
    {
        $id                   = $request->input('id');
        $userUpadate          = \App\User::find($id);

        $userUpadate->role = $request->input('role');
        $userUpadate->name    = $request->input('name');
        $userUpadate->password = $request->input('password');
        $userUpadate->email    = $request->input('email');

        if ($userUpadate->save()) {
            return redirect()->action('UsersController@index');
        }
    }

    public function saveAjax(Request $request)
    {
        $id                   = $request->input('id');
        $userUpadate          = \App\User::find($id);
        $userUpadate->name    = $request->input('name');
        $userUpadate->email    = $request->input('email');

        if ($userUpadate->save()) {
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
            return redirect()->action('UsersController@index');
        }
    }
}
