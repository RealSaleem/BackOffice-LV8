<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LowStockNotification extends Mailable
{
    use Queueable, SerializesModels;
    protected $outlet_name;
    protected $items;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->outlet_name = $data['outlet'];
        $this->items = $data['items'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = sprintf('Low Stock on Outlet %s',$this->outlet_name);
        
        return $this->subject($subject)->view('email.lowStockNotification', [
            'outlet_name' => $this->outlet_name , 
            'items' => $this->items , 
        ]);
    }
}
