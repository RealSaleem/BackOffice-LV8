<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\Store;
use Auth;

class NewSupplierRegistered extends Mailable
{
    use Queueable, SerializesModels;
    protected $supplier;
    protected $store;
    public $admin;
    protected $is_admin;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($supplier,$is_admin= false)
    {
        $this->supplier = $supplier;
        $this->is_admin = $is_admin;

        $this->admin = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'admin');
        })->where('store_id',Auth::user()->store_id)->first();                

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
            'is_admin' => $this->is_admin,
            'supplier' => $this->supplier,
            'store' => $this->store,
        ];

        if($this->is_admin){
            $subject = sprintf('Supplier %s Added Successfully by user %s',$this->supplier->name,Auth::user()->name);
            $data['admin'] = $this->admin;
        }else{
            $subject = sprintf('Added as a Supplier on Store %s',$this->store->name);
        }

        return $this->subject($subject)->view('email.newSupplierNotification', $data);
    }
}
