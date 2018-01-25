<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DateTime;

//Uses for import ICalendars
use ICal\ICal;
use Log;

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
        $iCalName = $room->id . "-" . $date->format("Y-m-d-H-i-s");

        //instance iCalendar
        $iCalendar = new \Eluceo\iCal\Component\Calendar('www.apartamentosierranevada.net');

        //gett all the book for send
        $books  = \App\Book::where('room_id',$room->id)
                        ->whereIn('type_book',[1, 2, 4, 7])
                        ->where('finish','>=' , $date->format("Y-m-d"))
                        ->orderBy('start','ASC')
                        ->get();

        //for each book we add a avent to the iCalendar
        foreach ($books as $book) {
            $vEvent = new \Eluceo\iCal\Component\Event();

            $vEvent->setDtStart(new \DateTime($book->start))
                    ->setDtEnd(new \DateTime($book->finish))
                    ->setNoTime(true)
                    ->setSummary("#ADMIN " . $book->customer->name);

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

    public function getImportICalendar($value='')
    {
        $array_icalendars_to_import = [
            [
                "id" => 1,
                "room_id" => 150,
                "ical_url_to_import" => "https://www.airbnb.es/calendar/ical/22508643.ics?s=ae3dbf6adbcf82580f66eb42e6bde359"
            ],
            [
                "id" => 2,
                "room_id" => 140,
                "ical_url_to_import" => "https://www.airbnb.es/calendar/ical/7031146.ics?s=25bb4fac0eff707c01a322ce143e6a6b"
            ]
        ];

        foreach ($array_icalendars_to_import as $ical_to_import) {
            //id releated with the icalendar to import
            $room_id = $ical_to_import["room_id"];

            //Read a iCal
            
            try {
                $ical = new ICal($ical_to_import["ical_url_to_import"]);
            } catch (\Exception $e) {
                Log::error("Error importing icalendar " . $ical_to_import["id"] . ". Error  message => " . $e->getMessage());
                continue;
            }
    
            // All events on iCal
            $events = $ical->sortEventsWithOrder($ical->events());

            foreach ($events as $event) {

            }
            
        }
        die();
    }
}