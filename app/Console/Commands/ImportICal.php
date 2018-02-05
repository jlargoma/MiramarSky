<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DateTime;
use DateInterval;
use ICal\ICal;
use Log;
use App\IcalImport;

class ImportICal extends Command
{
    /**
     * Customer ID for assign to the new books
     *
     * @var int
     */
    private $customer_id = 1780;

    /**
     * User ID for assign to the new books
     *
     * @var int
     */
    private $user_id = 39;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ical:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import ICal from agencies';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $this->importICalendar();
    }

    /**
     * Import ICaledar for the agencies
     */
    public function importICalendar()
    {
        $icalendars_to_import = IcalImport::all();
        
        foreach ($icalendars_to_import as $ical_to_import) {
            //id releated with the icalendar to import
            $room_id = $ical_to_import->room_id;

            //agency from where we are importing the calendar
            $agency = $this->getAgencyFromURL($ical_to_import->url);

            //Read a iCal            
            try {
                //FIXME: Use model of import ical
                $ical = new ICal($ical_to_import->url);
            } catch (\Exception $e) {
                Log::error("Error importing icalendar " . $ical_to_import->id . ". Error  message => " . $e->getMessage());
                echo $e->getMessage();
                continue;
            }
    
            // All events on iCal
            $events = $ical->sortEventsWithOrder($ical->events());

            $valid_events = [];

            foreach ($events as $event) {
                if ($this->isEventValidForAdd($event, $agency, $room_id)) {
                	// $valid_events[] = $event;
                    if (!$this->addBook($event, $agency, $room_id))
                        Log::error("Adding event => " . print_r($event,true));
                }
            }
            // file_put_contents("/var/www/vhosts/apartamentosierranevada.net/httpdocs/miramarski/test.json", json_encode($valid_events));
        }
    }

    /**
     * Add event to calendar
     * 
     * @param $event ICal\Event
     * @param $agency integer Agency from where come the book
     * @param $room_id Room belong the book
     */
    private function addBook(\ICal\Event $event, $agency, $room_id)
    {
        $start = new DateTime($event->dtstart);
        $finish = new DateTime($event->dtend);
        $interval = $start->diff($finish);
        $nights = $interval->format("%a");

        $book = new \App\Book();

        //Create Book
        $book->user_id       = $this->user_id;
        $book->customer_id   = ($agency == 1)? 1780: 1787;//$this->customer_id;// user to book imports / user to airbnb imports
        $book->room_id       = $room_id;
        $book->start         = $start->format("Y-m-d");
        $book->finish        = $finish->format("Y-m-d");
        $book->comment       = $event->summary;
        $book->book_comments = $event->summary . " #IMPORTING_TASK_SCHEDULED";
        $book->type_book     = 2;
        $book->nigths        = $nights;
        $book->agency        = $agency;
        return $book->save();
    }

    /**
     * Check if the event i valid for add
     * 1. finish date equal or bigger than today
     * 2. Do not exist a book with the same dates, 
     * same user, same agency, customer
     * 
     * @param $event ICal\Event
     * @param $agency integer Agency from where come the book
     * @param $room_id Room belong the book
     *
     * @return boolean
     */
    private function isEventValidForAdd(\ICal\Event $event, $agency, $room_id)
    {
        $date_now = new DateTime();
        $date_start_book = new DateTime($event->dtstart);
        $date_end_book = new DateTime($event->dtend);

        if ($date_now->format("Y-m-d") >= $date_end_book->format("Y-m-d"))
            return false;

        // if summary event start on #ADMIN, #BOOKING, #TRIVAGO, #BED&SNOW, #AIRBNB

        if (  preg_match("/^#ADMIN/", $event->summary ) || preg_match("/^#BOOKING/", $event->summary ) || preg_match("/^#TRIVAGO/", $event->summary ) || preg_match("/^#BED&SNOW/", $event->summary ) || preg_match("/^#AIRBNB/", $event->summary )
            )
           return false;


        $books  = \App\Book::where('room_id',$room_id)
                        // ->where('user_id', $this->user_id)
                        // ->where('customer_id', $this->customer_id)
                        ->where('start', $date_start_book->format("Y-m-d"))
                        ->where('finish', $date_end_book->format("Y-m-d"))
                        ->where('agency', $agency)
                        ->get();

        return count($books) == 0;
    }

    /**
     * Detect agency ical importer
     * 
     * @param $url
     *
     * @return integer
     */
    private function getAgencyFromURL($url)
    {
        $urls_agencies = [
            "/airbnb/" => 4,
            "/booking/" => 1
        ];

        foreach ($urls_agencies as $reg_agency => $agency_id) {
            if (preg_match($reg_agency, $url))
                return $agency_id;
        }

        //if we dont match any agency is a book of Jorge
        //but this is weird
        return 0;
    }
}
