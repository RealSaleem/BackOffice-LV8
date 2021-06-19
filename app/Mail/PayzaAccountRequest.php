<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;

class PayzaAccountRequest extends Mailable
{
    use Queueable, SerializesModels;

    protected $admin = null;
    protected $role = null;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$admin)
    {
        $this->admin = $admin;
        $this->role = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = sprintf('Request for new Payza Account by  store %s',Auth::user()->name);
        return $this->subject($subject)->view('email.vendorrequestmail', [
            'role'=> $this->role,
            // 'admin'=> $this->admin,
            'user'=> Auth::user(),
        ]);
    }
}
