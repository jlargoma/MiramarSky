<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend/customers/index',['customers' => \App\Customers::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   

        $customer = new \App\Customers();

        $customer->user_id = Auth::user()->id;
        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->phone = $request->input('phone');
        $customer->comments = $request->input('comment');

        if ($customer->save()) {
            return redirect()->action('CustomersController@index');
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
        $customer = \App\Customers::find($id);

        return view('backend/customer/_form',  [
                                                'customer' => $customer
                                            ]);
    }
    
    public function save(Request $request)
    {
        $id                   = $request->input('id');
        $customerUpadate          = \App\Customers::find($id);
        

        $customerUpadate->name     = $request->input('name');
        $customerUpadate->email    = $request->input('email');
        $customerUpadate->phone    = $request->input('phone');
        $customerUpadate->DNI      = $request->input('dni');
        $customerUpadate->address  = $request->input('address');
        $customerUpadate->comments = $request->input('comments');

        if ($customerUpadate->save()) {
            echo "Usuario cambiado!!";
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
        $customer = \App\Customers::find($id);

        if ($customer->delete()) {
            return redirect()->action('CustomersController@index');
        }
    }

    public function createExcel()
    {
        \Excel::create('Clientes', function($excel) {
            
            $arraycorreos = array();
            $correosUsuarios = \App\User::all();

            foreach ($correosUsuarios as $correos) {
                $arraycorreos[] = $correos->email;

            }


                $arraycorreos[] = "iankurosaki@gmail.com";
                $arraycorreos[] = "jlargoma@gmail.com";
                $arraycorreos[] = "victorgerocuba@gmail.com";



            $clientes = \App\Customers::whereNotIn('email',$arraycorreos)->where('email', '!=', ' ')->distinct('email')->get();
            // echo "<pre>";
            // echo count($clientes);
            // // print_r($pruebaclientes);
            // die();
            // $clientes = \App\Customers::distinct('email')->where('email','!=', "")->whereNotIn('email',$arraycorreos)->get();
            
            $excel->sheet('Clientes', function($sheet) use($clientes) {
         

            $sheet->freezeFirstColumn();

            $sheet->row(1, [
                'Número', 'Nombre', 'Email', 'Telefono'
            ]);

            foreach($clientes as $index => $user) {
                $sheet->row($index+2, [
                    $user->id, $user->name, $user->email, $user->phone
                ]); 
            }

        });
         
        })->export('xlsx');
    }
}
