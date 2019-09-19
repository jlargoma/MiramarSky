<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogsData extends Model
{
  protected $table = 'logs_data';
  private $registers = [
      'ical_airbnb',
      'ical_booking',
  ];
  
  
}
