<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function rooms()
    {
        return $this->hasOne('\App\Rooms', 'id', 'user_id');
    }

    public function book()
    {
        return $this->hasOne('\App\Book', 'user_id', 'id');
    }
	public function isAgent()
	{
		return ($this->hasOne('\App\AgentsRooms', 'user_id', 'id')) ? true : false;
	}
	public function agent()
	{
		return $this->hasOne('\App\AgentsRooms', 'user_id', 'id');
	}
}
