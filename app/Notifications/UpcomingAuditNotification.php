<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Audit;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class UpcomingAuditNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Audit $audit;

    /**
     * Create a new notification instance.
     */
    public function __construct(Audit $audit)
    {
        $this->audit = $audit;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Upcoming Audit Reminder')
            ->line('You have an upcoming audit assigned: ' . $this->audit->name)
            ->line('Department: ' . $this->audit->department)
            ->line('Due Date: ' . $this->audit->due_date->format('Y-m-d H:i'))
            ->action('View Audit', url('/audits/' . $this->audit->id))
            ->line('Please ensure you are prepared.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'audit_id' => $this->audit->id,
            'name' => $this->audit->name,
            'department' => $this->audit->department,
            'due_date' => $this->audit->due_date,
        ];
    }
}
