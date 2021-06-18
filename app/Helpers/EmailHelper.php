<?php
namespace App\Helpers;
use App\Models\StoreEmailTemplate;
use Auth;

class EmailHelper
{
    public static function send($slug,$data)
    {
    	$rows = StoreEmailTemplate::with(['transalation'])->where(['slug' => $slug])->get();

    	foreach ($rows as $key => $value) 
    	{
    		$subject = $value->transalation->first()->title;
    		$content = $value->transalation->first()->description;

    		$view_name = sprintf('email.emailLayouts.%s',strtolower($value->layout));
    		
    	}

    	/*
		@extends('email.emailLayouts.master')
		@section('content')
		@endsection

		$customer = Customer::where('id',$request->Orders->customer_id)->first();
		$emailCustomer = new NewPurchase($request->Orders);
		Mail::to($customer->email)->send($emailCustomer);	
		*/

        /*
		[store_id] => 121
		[template_name] => Setup Complete
		[subject_text] => Nextaxe Store Setup
		[sender_name] => nextaxe
		[receiver] => admin@nextaxe.com
		[type] => Nextaxe
		[layout] => Default
		[slug] => setup-complete
		*/
        
        echo '<pre>';print_r($rows->toArray());
    }

    public function build($subject,$view,$data)
    {
        return $this->subject($subject)->view($view, $data);
    }  

}