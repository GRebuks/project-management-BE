<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
class NewNotification extends Notification
{
    protected $title;
    protected $description;

    public function __construct($title = "Notification title", $description = "Notification description")
    {
        $this->title = $title;
        $this->description = $description;
    }

    public function toArray($notifiable)
    {
        return [
            'user_id' => $notifiable->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
