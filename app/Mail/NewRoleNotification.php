<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;

class NewRoleNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $store = null;
    protected $user = null;
    protected $role = null;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$role,$store)
    {
        $this->store = $store;
        $this->user = $user;
        $this->role = $role;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = sprintf('Role %s Added Successfully by user %s',$this->role->name,Auth::user()->name);

        return $this->subject($subject)->view('email.newRoleNotification', [
            'role'=> $this->role,
            'store'=> $this->store,
            'user'=> $this->user,
        ]);
    }
}
