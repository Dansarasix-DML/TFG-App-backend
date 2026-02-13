<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrder extends Mailable
{
    use Queueable, SerializesModels;
    protected User $user;
    protected Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Order $order) {
        $this->user = $user;
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            subject: 'Gracias por su compra en GameVerse!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            view: 'emails.neworder',
            with: [
                'name' => $this->user->name,
                'id' => $this->order->id,
                'price' => $this->order->price,
                'tax' => $this->order->tax,
                'total' => $this->order->total,
                'status' => $this->order->status,
            ]
        );
    }
}
