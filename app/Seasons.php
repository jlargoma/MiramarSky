<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class Seasons extends Model
{
    static function existDate($start,$finish)
    {
    	$fechas = \App\Seasons::all();
        $existStart = False;
        $existFinish = False;        
        $requestStart = Carbon::createFromFormat('d/m/Y',$start);
        $requestFinish = Carbon::createFromFormat('d/m/Y',$finish);

        foreach ($fechas as $fecha) {
            if ($existStart == False && $existFinish == False) {
                $start = Carbon::createFromFormat('Y-m-d', $fecha->start_date);
                $finish = Carbon::createFromFormat('Y-m-d', $fecha->finish_date);                
                $existStart = Carbon::create($requestStart->year,$requestStart->month,$requestStart->day)->between($start, $finish);
                $existFinish = Carbon::create($requestFinish->year,$requestFinish->month,$requestFinish->day)->between($start, $finish);
            }
        }
        if ($existStart == False && $existFinish == False) {
        	return False;
        }else{
        	return True;
        }
    }

    
    public function typeSeasons()
        {
            return $this->hasOne('\App\TypeSeasons', 'id', 'type');
        }


    public static function getSeason($start)
        {
            // echo $start." ";

            $season = \App\Seasons::where('start_date' , '<=' , $start )
                                    ->where('finish_date', '>=' , $start)->get();

            return $season[0]->type;
        }
}
