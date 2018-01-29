<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DateTime;
use DateInterval;

class ICalendarController extends Controller
{
    /**
     * Get the icalendar for the specific
     * apto id passed
     *
     * @param $request Request
     * @param $aptoID integer The apto id for get the icalendar
     *
     * @return icalendar a file in format icalendar
     */
    public function getIcalendar(Request $request, $aptoID)
    {
        /*Get the Room for export the Ical*/
        $room = \App\Rooms::find($aptoID);

        //if the room is not set is because doesn´t exist
        if (!$room)
            return;

        //current date for use in the controller
        $date = new DateTime();

        //Set ical name roomID+date
        $iCalName = $room->nameRoom . "-" . $date->format("Y-m-d-H-i-s");

        //instance iCalendar
        $iCalendar = new \Eluceo\iCal\Component\Calendar('www.apartamentosierranevada.net');

        //gett all the book for send
        $books  = \App\Book::where('room_id',$room->id)
                        ->whereIn('type_book',[1, 2, 4, 7])
                        ->where('finish','>=' , $date->format("Y-m-d"))
                        ->orderBy('start','ASC')
                        ->get();

        //this is for set from where come the book
        $start_summary = [
            0 => "#ADMIN" ,
            1 => "#BOOKING", 
            2 => "#TRIVAGO", 
            3 => "#BED&SNOW", 
            4 => "#AIRBNB"
        ];

        //for each book we add a avent to the iCalendar
        foreach ($books as $book) {
            $vEvent = new \Eluceo\iCal\Component\Event();

            $in = new \DateTime($book->start);
            $out = new \DateTime($book->finish);
            $out->sub(new \DateInterval('P1D'));
            $vEvent->setDtStart($in)
                    ->setDtEnd($out)
                    ->setNoTime(true)
                    ->setSummary($start_summary[$book->agency] . " " . $book->customer->name);                   

            //Add event to the iCalendar
            $iCalendar->addComponent($vEvent);

            //clear var for avoid reperetaed events
            $vEvent = null;
        }

        //Export the iCalendar for the requested room
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$iCalName.'.ics"');
        echo $iCalendar->render();
    }
}
