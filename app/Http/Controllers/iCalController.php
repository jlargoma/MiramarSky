<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use \Carbon\Carbon;
use Auth;
use App\Classes\Mobile;

use ICal\ICal;

class iCalController extends Controller
{
    public function create(Request $request)
    {
    	/*Create a iCal*/
    	$iCalName = 'demo-ical';
    	$vCalendar = new \Eluceo\iCal\Component\Calendar('www.example.com');

    	$vEvent = new \Eluceo\iCal\Component\Event();

    	$vEvent->setDtStart(new \DateTime('2012-12-24'))
			    ->setDtEnd(new \DateTime('2012-12-24'))
			    ->setNoTime(true)
			    ->setSummary('Christmas');


		$vCalendar->addComponent($vEvent);

		header('Content-Type: text/calendar; charset=utf-8');
		header('Content-Disposition: attachment; filename="'.$iCalName.'.ics"');

		echo $vCalendar->render();
    }

    public function read(Request $request)
    {
    	//Read a iCal

		try {
		    $ical = new ICal('/icals/demo.ics', array(
		        'defaultSpan'                 => 2,     // Default value
		        'defaultTimeZone'             => 'UTC',
		        'defaultWeekStart'            => 'MO',  // Default value
		        'disableCharacterReplacement' => false, // Default value
		        'skipRecurrence'              => false, // Default value
		        'useTimeZoneWithRRules'       => false, // Default value
		    ));
		    // $ical->initFile('ICal.ics');
		    // $ical->initUrl('https://raw.githubusercontent.com/u01jmg3/ics-parser/master/examples/ICal.ics');
		} catch (\Exception $e) {
		    die($e);
		}

		$forceTimeZone = false;

		// All events on iCal
		$events = $ical->sortEventsWithOrder($ical->events());
		echo "<pre>";
		foreach ($events as $event) :
			print_r($event);
		endforeach;

		die();

    }
}
