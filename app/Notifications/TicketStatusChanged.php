<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketStatusChanged extends Notification
{
    use Queueable;

    protected $ticket;

    /**
     * Erstelle eine neue Notification-Instanz.
     *
     * @param array|object $ticket Das Ticket, dessen Status sich geändert hat
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Bestimme die Kanäle, über die die Notification gesendet wird.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Sende die Benachrichtigung per E-Mail.
        return ['mail'];
    }

    /**
     * Baue die Mail-Nachricht auf.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Statusänderung für Ticket #' . $this->ticket['id'])
                    ->line('Der Status deines Tickets "' . $this->ticket['title'] . '" wurde geändert.')
                    ->line('Neuer Status: ' . ucfirst($this->ticket['status']))
                    ->action('Ticket ansehen', url('/tickets/' . $this->ticket['id']))
                    ->line('Vielen Dank, dass du unser Supportsystem nutzt!');
    }

    /**
     * Array-Darstellung der Notification (optional für Datenbank-Channel).
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket['id'],
            'status' => $this->ticket['status'],
            'title' => $this->ticket['title'],
        ];
    }
}
