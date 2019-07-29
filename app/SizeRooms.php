<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SizeRooms extends Model
{
    protected $table = 'sizerooms';

    public function rooms()
    {
        return $this->hasMany('\App\Rooms', 'sizeApto', 'id');
    }

    public function getRoomsFastPayment()
    {
        $count = 0;
        $rooms = $this->rooms();
        foreach ($rooms as $index => $room)
        {
            if ($rooms->fast_payment == 1)
                $count++;
        }

        return $count;
    }
}
