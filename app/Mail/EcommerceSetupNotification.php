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

class EcommerceSetupNotification extends Mailable
{
    use Queueable, SerializesModels;
    protected $store;
    public $admin;
    public $webStore;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($webStore)
    {
        $this->webStore = $webStore;

        $this->admin = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'admin');
        })->where('store_id',Auth::user()->store_id)->first();

        $this->store = Store::find(Auth::user()->store_id);        
        $this->outlet = Outlet::find($webStore->outlet_id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'store' => $this->store,
            'admin' => $this->admin,
            'webStore' => $this->webStore
        ];

        $subject = sprintf('Go Eecommerce setup successfully for outlet %s',$this->outlet->name);

        return $this->subject($subject)->view('email.ecommerceSetupNotificaton', $data);
    }
}