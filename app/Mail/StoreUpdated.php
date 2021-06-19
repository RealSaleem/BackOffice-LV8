<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Store;
use App\Models\User;
use Auth;

class StoreUpdated extends Mailable
{
    use Queueable, SerializesModels;
    protected $store;
    public $admin;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;        
        $this->admin = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'admin');
        })->where('store_id',$user->store_id)->first();        

        $this->store = Store::find($user->store_id);        
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
            'user' => $this->user
        ];

        $subject = sprintf('Store %s Updated Successfully by user %s',$this->store->name,$this->user->name);

        return $this->subject($subject)->view('email.storeUpdatedNotification', $data);
    }
}
