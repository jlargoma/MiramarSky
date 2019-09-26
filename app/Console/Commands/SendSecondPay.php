<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\Book;
use App\Traits\BookEmailsStatus;
use Illuminate\Support\Facades\DB;
use App\Settings;
use Carbon\Carbon;

class SendSecondPay extends Command {

  use BookEmailsStatus;
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'secondPay:sendEmails';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Check books checkin into 15 days and send emials';
  
  private $message;

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $this->checkSecondPay();
  }

  /**
   * Check the books into 15 (or settings) days
   */
  public function checkSecondPay() {
    
    $daysToCheck = \App\DaysSecondPay::find(1)->days;
    $today = Carbon::now();
    
    $books = Book::where('start', '>=', $today)
            ->where('start', '<=', $today->copy()->addDays($daysToCheck))
            ->where('type_book', 2)
//            ->where('send', 0)
            ->orderBy('created_at', 'DESC')->get();

//    dd($books); 
     
    $subject = 'Recordatorio Pago '.env('APP_NAME').' ';
    if ($books)
    {
      foreach ($books as $book){
        
        if (!empty($book->customer->email)){
          // check the pending amount
          $totalPayment = 0;
          $payments = \App\Payments::where('book_id', $book->id)->get();
          if ($payments){
            foreach ($payments as $key => $pay)
            {
                $totalPayment += $pay->import;
            }
          }
          $pending = ($book->total_price - $totalPayment);
          
          echo $subject . $book->customer->name.', checkin: '.$book->start.', pendiente: '.$pending."\n";
          continue;
          
          if ($pending>0)
            $this->sendEmail_secondPayBook($book,$subject . $book->customer->name);
          $book->send = 1;
          $book->save();
        }
      }
    }
  }
  
 

}
