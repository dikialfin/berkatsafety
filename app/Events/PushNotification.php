<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PushNotification implements ShouldBroadcast {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $notification;

  public function __construct($notification) {
    $this->notification = $notification;
  }

  public function broadcastOn() {
    $notif_id = $this->notification->notif_id;
    return new Channel("notification-$notif_id");
  }

  public function broadcastAs(): string
    {
        return 'new-notification';
    }

  public function broadcastWith() {
    return [
      'title' => $this->notification->title,
      'message' => $this->notification->message,
      'target_url' => $this->notification->target_url
    ];
  }
}