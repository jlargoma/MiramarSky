<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Carbon\Carbon;
use Mail;
use App\Classes\Mobile;
class QuestionsController extends Controller
{

	public static 	$meetUs = [
                        		"Portal de Internet",
                    			"A través de un amigo",
                    			"Agencias",
                    			"Otros",
					];

    public function index($id)
    {
        $book = \App\Book::find( base64_decode($id) );
		return view('frontend.questions.index', [
                                                    'mobile'    => new Mobile(),
                            						'book'      => $book,
                            						'questions' => \App\Questions::take(6)->get(),
                            						'vote'      => 0,
                            					]);
    }

    public function vote(Request $request){
    	for ($i = 1; $i <= count($request->input('question')); $i++) {
    		if (!empty(	$request->input('question')[$i])) {
    			$answer = new \App\Answers();
    			$answer->question_id = $i;
    			$answer->rate = $request->input('question')[$i];
    			$answer->date = Carbon::now()->format('Y-m-d');
                $answer->status = 1;
    			$answer->room_id = $request->input('room_id');
    			$answer->save();
    		}
    		
    	}

		return view('frontend.questions.index', [
                            						'mobile'    => new Mobile(),
                            						'questions' => \App\Questions::take(6)->get(),
                            						'vote'      => 1,
                            					]);
    }


    public function admin($year='', $apto='')
    {
        if ( empty($year) ) {
            $date = Carbon::now();
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();
        }

        if ($date->copy()->format('n') >= 9) {
            $start = new Carbon('first day of September '.$date->copy()->format('Y'));
        }else{
            $start = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
        }

        $data = array();
        $questions = \App\Questions::where( 'id','!=',7 )->get();
        foreach (\App\Rooms::where('state', 1)->get() as $key => $room) {
            
            foreach ($questions as $key => $question) {
                $data[$room->id][$question->id] = $this->getDataByQuestion( $question->id, $room->id, $start->copy()->format('Y') );
            }
            
        }
        if ($apto == '') {
            $apto = "all";
            $answers = \App\Answers::where( 'date','>',$start->copy() )->where('date','<',$start->copy()->addYear())->get();
            $comments = \App\Answers::where( 'question_id', 7 )->get();

        } else {

            $answers = \App\Answers::where( 'date','>',$start->copy() )
                                    ->where('date','<',$start->copy()->addYear())
                                    ->where('room_id', $apto)
                                    ->get();
            $comments = \App\Answers::where( 'question_id', 7 )->where('room_id', $apto)->get();
        }
        
        
        $encuestas = round(count($answers) / 7);


        return view('backend.questions.index', [
                                                    'questions' => $questions,
                                                    'comments' => $comments,
                                                    'mobile'    => new Mobile(),
                                                    'encuestas' => $encuestas,
                                                    'data'      => $data,
                                                    'date'      => $start,
                                                    'rooms'     => \App\Rooms::where('state', 1)->get(),
                                                    'apto'      => $apto,
                                                ]);
    }


    static function getDataByQuestion($question_id, $room_id="", $year="")
    {
        if ( empty($year) ) {
            $date = Carbon::now();
        }else{
            $year = Carbon::createFromFormat('Y',$year);
            $date = $year->copy();
        }

        if ($date->copy()->format('n') >= 9) {
            $start = new Carbon('first day of September '.$date->copy()->format('Y'));
        }else{
            $start = new Carbon('first day of September '.$date->copy()->subYear()->format('Y'));
        }

        if ($room_id != "") {
            $answers = \App\Answers::where('question_id', $question_id)
                                    ->where('room_id', $room_id)
                                    ->where( 'date','>',$start->copy() )
                                    ->where('date','<',$start->copy()->addYear())
                                    ->get();
        } else {
            $answers = \App\Answers::where('question_id', $question_id)
                                ->where( 'date','>',$start->copy() )
                                ->where('date','<',$start->copy()->addYear())
                                ->get();
        }
        

        
        $data = ['total' => 0, 'avg' => 0];

        if (count($answers) > 0) {
            foreach ($answers as $key => $answer) {
                $data['total']++;
                $data['avg'] += $answer->rate;
            }

            $data['avg'] = round($data['avg']/$data['total'], 2);

        }    

        return $data;
    }

}
