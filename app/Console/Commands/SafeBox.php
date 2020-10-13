<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Illuminate\Support\Facades\DB;
use App\Book;
use App\Rooms;
use App\SafetyBox;
use App\BookSafetyBox;
use App\Traits\BookEmailsStatus;

class SafeBox extends Command {

  use BookEmailsStatus;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'SafeBox:asignAndSend';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Asign the SafeBox to checking at 10am and send the mail';
  private $oSafetyBox;

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
    $this->oSafetyBox = new SafetyBox();
    $this->process();
  }

  private function process() {

    $today = date('Y-m-d');
    $safeBooxLst = $this->oSafetyBox->get();
    $sb_by_room = [];
    $sb_by_ID = [];
    if (!$safeBooxLst)
      return null;

    foreach ($safeBooxLst as $sb){
      $sb_by_room[$sb->room_id] = $sb;
      $sb_by_ID[$sb->id] = $sb;
    }

    $books = Book::whereIn('type_book', [1, 2])
            ->where('start', '=', $today)
            ->get();
    if ($books) {
      /*****************************************************************/
      /**********     ASIGNAR                         ******************/
      foreach ($books as $book) {
        $box = isset($sb_by_room[$book->room_id]) ? $sb_by_room[$book->room_id] : null;
        if (!$box)
          continue;

        if (!$this->oSafetyBox->usedBy_day($box->id, $book->start, $book->finish)) {
          $this->oSafetyBox->newBookSafetyBox($book->id, $box->id);
        }
      }
      /*****************************************************************/
      /**********     ENVIAR MAIL                     ******************/
      foreach ($books as $book) {
        $BookSafetyBox = BookSafetyBox::where('book_id', $book->id)
                ->whereNull('deleted')
                ->whereNull('log')
                ->first();
       
        if ($BookSafetyBox) {
          $box = isset($sb_by_ID[$BookSafetyBox->box_id]) ? $sb_by_ID[$BookSafetyBox->box_id] : null;
          if ($box){
            $sended = $this->sendSafeBoxMensaje($book, 'book_email_buzon', $box);
            if ($sended)
              $box->updLog($book->id, 'sentMail');
          } 
        }
      }
    }
  }

}
