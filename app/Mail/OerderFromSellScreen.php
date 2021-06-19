<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Store;
use App\Models\Outlet;
use App\Models\User;
use Auth;

class OerderFromSellScreen extends Mailable
{
    use Queueable, SerializesModels;
    protected $order;
    // protected $is_admin;
    // protected $is_supplier;
    // protected $purchase_order;
    // protected $store;
    // protected $outlet;
    // public $admin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order,$customer)
    {
        $this->order = $order;
        $this->customer = $customer;
        $this->store = Store::find(Auth::user()->store_id);        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = null;

        $data = [
            'order' => $this->order,
            'customer' => $this->customer,
            'store' => $this->store,
            'user' => Auth::user()
        ];

        $subject = sprintf('Order purchased successfully from %s',$this->store->name);
       

        return $this->subject($subject)->view('email.OerderFromSellScreen', $data);
    }
}