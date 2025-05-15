<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\ActionPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class OverdueActionPlanNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public ActionPlan $actionPlan;

    /**
     * Create a new notification instance.
     */
    public function __construct(ActionPlan $actionPlan)
    {
        $this->actionPlan = $actionPlan;
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
            ->subject('Overdue Action Plan')
            ->line('An action plan assigned to you is overdue: ' . $this->actionPlan->action_item)
            ->line('Due Date: ' . $this->actionPlan->due_date)
            ->action('View Action Plan', url('/action-plans/' . $this->actionPlan->id))
            ->line('Please address this action plan as soon as possible.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'action_plan_id' => $this->actionPlan->id,
            'action_item' => $this->actionPlan->action_item,
            'due_date' => $this->actionPlan->due_date,
        ];
    }
}
