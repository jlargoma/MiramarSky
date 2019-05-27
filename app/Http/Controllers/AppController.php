<?php
/**
 * Created by PhpStorm.
 * User: Ian Avila
 * Date: 27/05/2019
 * Time: 19:56
 */

namespace App\Http\Controllers;


class AppController extends Controller
{
	protected static function getActiveYear()
	{
		$activeYear = \App\Years::where('active', 1)->first();
		return $activeYear;
	}
}