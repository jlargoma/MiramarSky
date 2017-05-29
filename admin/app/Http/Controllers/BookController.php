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
       

        return view('backend/planning/index',[
                                                'newbooks'  => \App\Book::newBooks(),
                                                'countnews' =>count(\App\Book::newBooks()),
                                                
                                                'oldbooks'  => \App\Book::oldBooks(),
                                                'countold'  =>count(\App\Book::oldBooks()),
                                                
                                                'proxbooks' => \App\Book::proxBooks(),
                                                'countprox' =>count(\App\Book::proxBooks()),
                                                
                                                'bloqbooks' => \App\Book::bloqBooks(),
                                                'countbloq' =>count(\App\Book::bloqBooks()),
                                                
                                                'subbooks'  => \App\Book::subBooks(),
                                                'countsub'  =>count(\App\Book::subBooks()),

                                                'rooms'     => \App\Rooms::all(),
                                                'date'      => $date,

                                                ]);
    }

    public function newBook(){

        $max = \App\SizeRooms::max('maxOcu');
        $min = \App\SizeRooms::min('minOcu');

        return view('backend/planning/_form',[
                                                'book' => new \App\Book(),
                                                'rooms' => \App\Rooms::all(),
                                                'min' => $min,
                                                'max' => $max,
                                            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $book = new \App\Book();
        echo "<pre>";
            if ($book->existDate($request->start,$request->finish,$request->room)) {
                return "va bien";
            }else{
                return "va mal";
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
    public function update(Request $request, $id)
    {
        
    }



    public function changeBook(Request $request, $id)
        {
            if ( isset($request->room) && !empty($request->room)) {
                $book = \App\Book::find($id);

                if ($book->changeBook("",$request->room)) {
                    return "OK";
                }else{
                    return "Ya hay una reserva para ese apartamento";
                }

                    
            }
            if ( isset($request->status) && !empty($request->status)) {
                $book = \App\Book::find($id);
                
                if ($book->changeBook($request->status,"")) {
                    return "Estado cambiado";
                }else{
                    return "No se puede cambiar el estado";
                }
            }else{
                return "Valor nulo o vacio";
            }
        }

    static function getPriceBook(Request $request){
            
        $book = new \App\Book();
        $price = $book->getPriceBook($request->input('start'),$request->input('finish'),$request->input('pax'),$request->input('room'));
        // echo $price;
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
