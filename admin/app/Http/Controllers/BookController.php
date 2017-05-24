<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // if ( !isset($_GET['month']) || !isset($_GET['year'])) {
            $date = Carbon::now();
        // }else{
            // $date = Carbon::createFromFormat('Y-m',$_GET['year']."-".$_GET['month']);
        // }


        return view('backend/planning/planning',[
                                                'books'      => \App\Book::all(),
                                                'rooms'      => \App\Rooms::all(),
                                                'date'       => $date,
                                                ]);
    }

    public function newBook(){

        return view('/backend/planning/new-book',[
                                                    'rooms' => \App\Rooms::all()
                                                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function update(Request $request, $id)
    {
        
    }



    public function changeBook(Request $request, $id)
    {
        if ( isset($request->room) && !empty($request->room)) {
            $book = \App\Book::find($id);
            echo "<pre>";
            print_r($book->changeBook("",$request->room));
            die();

            if ($book->changeBook("",$request->room)) {
                return "OK";
            }else{
                return "Ya hay una reserva para ese apartamento";
            }
        }
        if ( isset($request->status) && !empty($request->status)) {
            $book = \App\Book::find($id);
            
            if ($book->changeBook($request->status,"")) {
                return "OK";
            }
        }else{
            return "Valor nulo o vacio";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
