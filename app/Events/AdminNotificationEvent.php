<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
// use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class AdminNotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
  
    public $notification;
  
    public function __construct($notification) {
      $this->notification = $notification;
    }
  
    public function broadcastOn() {
      $user_id = $this->notification->user_id;
      return new Channel("notification-$user_id");
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

