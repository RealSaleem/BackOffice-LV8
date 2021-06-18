<?php
namespace App\Helpers;
use App\Providers\Enum;

class OrderType extends Enum {
    const In = 1;
    const Out = 2;
    const Sale = 3;
    const Return = 4;
    const StockReturn = 5;
    const TransferIn = 6;
    const TransferOut = 7;
    const CompositionIn = 8;
    const CompositionOut = 9;
}
