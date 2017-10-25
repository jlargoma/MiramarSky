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
        $user->phone = $request->input('phone');
        $user->role = $request->input('role');
        $user->remember_token = str_random(60);
        $user->password = bcrypt($request->input('password'));
        

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
        
        
        $userUpadate->name = $request->input('name');
        $userUpadate->email = $request->input('email');
        $userUpadate->phone = $request->input('phone');
        $userUpadate->role = $request->input('role');
        $userUpadate->remember_token = str_random(60);
        $userUpadate->password = bcrypt($request->input('password'));

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


    public function createPasswordUser(Request $request, $email)
    {

        if (request()->getMethod() == 'POST') {

            $data = $request->input();

            
            if ($data['password'] == $data['rep-password']) {
                # code...
            }else{
                echo "Error, las contraseñas no coinciden";
            }
            




        }else{


            $email = base64_decode($email);
            $user = \App\User::where('email', $email)->first();//->where('role', 'LIKE', '%propietario%')->get();

            if (count($user) > 0) {
                
                if ( preg_match('/propietario/i', $user->role) ) {



                    return view('loginToOwneds', ['user' => $user ]);


                }else{
                    echo "Error, este usuario no es un propietario";

                }


            }else{

                echo "Error, no hay ningun usuario para este email";

            }

        }

        
    }

}
