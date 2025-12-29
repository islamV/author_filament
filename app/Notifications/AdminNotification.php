<?php

namespace App\Notifications;

use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $body,
        public ?string $icon = null,
        public ?string $color = 'info',
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'icon' => $this->icon ?? 'heroicon-o-bell',
            'color' => $this->color,
        ];
    }

    /**
     * Helper method to send a Filament notification to an admin
     */
    public static function sendToAdmin($admin, string $title, string $body, ?string $icon = null, ?string $color = 'info'): void
    {
        $admin->notify(new self($title, $body, $icon, $color));
        
        // Also send a Filament toast notification if the admin is currently viewing the panel
        FilamentNotification::make()
            ->title($title)
            ->body($body)
            ->icon($icon ?? 'heroicon-o-bell')
            ->color($color)
            ->send();
    }
}

