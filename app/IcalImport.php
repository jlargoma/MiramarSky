<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IcalImport extends Model
{
    protected $kink    = 0;
    protected $room_id = 0;


    public function getLink()
    {
    	return $this->link;
    }

    public function setLink($link)
    {
    	$this->link = $link;
    	return $this->link;
    }

    public function getRoom()
    {
    	return $this->room_id;
    }

    public function setRoom($room_id)
    {
    	$this->room_id = $room_id;
    	return $this->room_id;
    }

    
}
