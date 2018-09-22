<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialSegment extends Model
{
	protected $table = 'special_segments';
	protected $fillable = ['start', 'finish', 'minDays'];
}
