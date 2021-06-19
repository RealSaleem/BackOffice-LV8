<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Store;
use App\Models\Outlet;

class NewUserRegistered extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $store;
    protected $outlet;
    protected $is_admin;
    protected $pass;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$is_admin,$pass)
    {
        $this->user = $user;
        $this->is_admin = $is_admin;
        $this->pass = $pass;
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
            'user' => $this->user,
            'store' => $this->store,
            'pass' => $this->pass,
        ];

        if($this->is_admin){
            $subject = sprintf('Supplier %s Added Successfully by user %s',$this->supplier->name,Auth::user()->name);
            $data['admin'] = $this->admin;
        }else{
            $subject = sprintf('Added as a Supplier on Store %s',$this->store->name);
        }

        return $this->subject($subject)->view('email.newUserNotification', $data);




        $store = Store::where('id',$this->user->store_id)->first();
        $outlet = Outlet::where('id',$this->user->outlet_id)->first();
        return $this->view('email.newUserNotification', ['user' => $this->user, 'store' => $store, 'outlet' => $outlet]);
    }
}
