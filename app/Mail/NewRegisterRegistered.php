<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Store;
use App\Models\Outlet;

use Auth;

class NewRegisterRegistered extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $register;
    protected $type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$register,$type)
    {
        $this->user = $user;
        $this->register = $register;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $store = Store::where('id',Auth::user()->store_id)->first();
        $outlet = Outlet::where('id',$this->register->outlet_id)->first();
        return $this->view('email.newRegisterNotification',[
            'user' => $this->user,
            'store' => $store, 
            'outlet' => $outlet,
            'register' => $this->register,
            'type' => $this->type,
        ]);
    }
}
