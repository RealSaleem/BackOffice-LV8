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

class PartialPurchaseNotification extends Mailable
{
    use Queueable, SerializesModels;
    protected $supplier;
    protected $is_admin;
    protected $is_supplier;
    protected $purchase_order;
    protected $store;
    protected $outlet;
    public $admin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($supplier,$purchase_order,$is_supplier,$is_admin)
    {
        $this->supplier = $supplier;
        $this->is_admin = $is_admin;
        $this->is_supplier = $is_supplier;
        $this->purchase_order = $purchase_order;

        $this->admin = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'admin');
        })->where('store_id',Auth::user()->store_id)->first();                

        $this->store = Store::find(Auth::user()->store_id);        
        $this->outlet = Outlet::find($this->purchase_order->dest_outlet_id);
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
            'is_admin' => $this->is_admin,
            'is_supplier' => $this->is_supplier,
            'store' => $this->store,
            'outlet' => $this->outlet,
            'purchase_order' => $this->purchase_order,
            'supplier' => $this->supplier,
            'user' => Auth::user()
        ];

        if($this->is_admin){
            $subject = sprintf('Partial Order Sent Successfully from outlet %s by user %s',$this->outlet->name, Auth::user()->name);
            $data['admin'] = $this->admin;
        }else if($this->is_supplier){
            $subject = sprintf('Receive a Partial Order from outlet %s of Store %s',$this->outlet->name,$this->store->name);
        }else{
            $subject = sprintf('Partial Order Sent Successfully for outlet %s',$this->outlet->name);
        }


        return $this->subject($subject)->view('email.partialPurchaseNotification', $data);
    }
}