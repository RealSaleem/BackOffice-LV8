<?php
namespace App\Helpers;

use DatePeriod;
use DateTime;
use DateInterval;
 
class DateHelper {

    public static function getDates($request)
    {
        $end_date = new DateTime('now');
        $diff = new DateTime('now');
        $start_date = $diff->modify(sprintf('-%s day',$request->day));

        if(isset($request->start_date) && isset($request->end_date))
        {
            $start_date = DateTime::createFromFormat('Y-m-d',$request->start_date );
            $end_date = DateTime::createFromFormat('Y-m-d',$request->end_date );
        }        

        $start_date = $start_date->format('Y-m-d');
        $end_date = $end_date->format('Y-m-d');
        
        $data = [ $start_date, $end_date ];

        return $data;
    } 
}