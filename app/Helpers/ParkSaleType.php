<?php
namespace App\Helpers;
use App\Providers\Enum;

class ParkSaleType extends Enum {
    const Parked = 'Parked';
    const Resumed = 'Resumed';
    const Unpaid = 'Unpaid';
    const paid = 'Paid';
   
}


