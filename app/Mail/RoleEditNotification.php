<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;

class RoleEditNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $admin = null;
    protected $role = null;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($role,$admin)
    {
        $this->admin = $admin;
        $this->role = $role;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = sprintf('Role %s Updated Successfully by user %s',$this->role->name,Auth::user()->name);

        return $this->subject($subject)->view('email.roleEditNotification', [
            'role'=> $this->role,
            'admin'=> $this->admin,
            'user'=> Auth::user(),
        ]);
    }
}
