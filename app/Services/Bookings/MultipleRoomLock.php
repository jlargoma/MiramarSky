<?php

namespace App\Services\Bookings;

use App\Rooms;
use App\Customers;
use App\Book;
use App\Settings;
use Illuminate\Support\Facades\Auth;
use App\Services\OtaGateway\Config;

/**
 * Description of MultipleRoomLock
 *
 * @author cremonapg
 */
class MultipleRoomLock {

  public function roomLockBy_ChannelGr($cg, $start, $finish) {

    $oBookings = new Book();
    $customerID = null;
    $aRoomsLst = Rooms::where('channel_group', $cg)->get();
    $oUser = Auth::user();
    $roomID = null;
    foreach ($aRoomsLst as $room) {
      if ($oBookings->availDate($start, $finish, $room->id)) {
        $roomID = $room->id;
        if (!$customerID) {
          $oCustomers = new Customers();
          $oCustomers->user_id = $oUser ? $oUser->id : 1;
          $oCustomers->name = 'Bloqueo ' . ($oUser ? $oUser->name : 'automatico');
          $oCustomers->save();
          $customerID = $oCustomers->id;
        }
        $book = new Book();
        $book->user_id = $oUser ? $oUser->id : 1;
        $book->customer_id = $customerID;
        $book->room_id = $room->id;
        $book->start = $start;
        $book->finish = $finish;
        $book->nigths = calcNights($start, $finish);
        $book->type_book = 4;
        $book->save();
      }
    }
    if ($roomID) {
      $oBookings->sendAvailibility($roomID, $start, $finish);
    }
  }
  
  public function get_RoomLockSetting($oRooms) {
    
    $oTaskData = Settings::findOrCreate('multiple_room_lock',null);
    $aTaskData = json_decode($oTaskData->content,true);
    if (!$aTaskData){
      $aTaskData = ['time'=>0,'rooms'=>[]];
    }
    foreach ($oRooms as $k=>$v){
      if (!isset($aTaskData['rooms'][$k]))
        $aTaskData['rooms'][$k] = null;
    }
    $oTaskData->content = json_encode($aTaskData);
    $oTaskData->save();
    
    return $aTaskData;
  }
  
  public function set_RoomLockSetting($aTaskData) {
    
    $oTaskData = Settings::findOrCreate('multiple_room_lock',null);
    $oTaskData->content = json_encode($aTaskData);
    $oTaskData->save();
  }

  public function getRoomsName() {
    $oConfig = new Config();
    return $oConfig->getRoomsName();
  }
}
