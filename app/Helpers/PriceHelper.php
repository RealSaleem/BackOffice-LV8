<?php

namespace App\Helpers;
use Auth;

class PriceHelper 
{
	public static function number_format($number)
	{
        $round_off = Auth::user()->store->round_off;
        
		if(is_null($round_off)){
			$round_off = 2;
		}
		return sprintf('%s %s',Auth::user()->store->default_currency,number_format($number,(int)$round_off));
	}

	public static function number_format_without_currency($number)
	{
        $round_off = Auth::user()->store->round_off;
        
		if(is_null($round_off)){
			$round_off = 2;
		}
		return sprintf('%s',number_format($number,$round_off, '.', ''));
	}

	public static function get_currency()
	{
		return Auth::user()->store->default_currency;
	}
}

