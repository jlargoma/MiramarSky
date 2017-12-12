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

    public function index()
    {

		return view('frontend.questions.index', [
                            						'mobile'    => new Mobile(),
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
    			$answer->save();
    		}
    		
    	}

		return view('frontend.questions.index', [
                            						'mobile'    => new Mobile(),
                            						'questions' => \App\Questions::take(6)->get(),
                            						'vote'      => 1,
                            					]);
    }
}
