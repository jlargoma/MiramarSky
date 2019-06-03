<?php
/**
 * Created by PhpStorm.
 * User: Ian Avila
 * Date: 27/05/2019
 * Time: 19:56
 */

namespace App\Http\Controllers;

use App\Years;
use Illuminate\Http\Request;

class AppController extends Controller
{
	/**
	 * @return mixed
	 */
	protected static function getActiveYear()
	{
		$activeYear = Years::where('active', 1)->first();
		return $activeYear;
	}

	/**
	 * @param array $params
	 *  params must be content:
		- dates
		- pax
	 * @return html-form  with rooms avaliable to book and pay
	 */
	protected function searchRoomsAvaliables(array $params)
	{
		//TODO refactoring this function to look for rooms with capacity to accommodate the guests and that area
		// vailable to reserve


	}
}