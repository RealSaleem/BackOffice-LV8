<?php
namespace App\Helpers;
use Jawira\CaseConverter\Convert;

class TextHelper {
    public static function convertToDashedCase($slug)
    {
    	$hero = new Convert($slug);
		return $hero->toKebab();   // output: johnConnor
    }
}


