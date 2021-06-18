<?php
namespace App\Helpers;
use App\Providers\Enum;

class OrderStatus extends Enum {
	const Failed = 'Failed';
	const Confirmed = 'Confirmed';
	const Processing = 'Processing';
	const OnTheWay = 'On The Way';
	const Complete = 'Complete';
	const ReturnRequested = 'Return Requested';
	const ReturnApproved = 'Return Approved';
	const Refunded = 'Refunded';
	const Cancelled = 'Cancelled';
	const Void = 'Return Void';
}
