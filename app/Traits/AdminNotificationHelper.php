<?php

namespace App\Traits;

use App\Events\AdminNotificationEvent;
use App\Models\AdminNotification;
use App\Models\Notification;
use App\Models\User;

trait AdminNotificationHelper
{
  public function sendNotification(string $title, string $message, User $user, ?string $type = null, ?int $object_id = null){
    $n = new AdminNotification();
    $n->user_id = $user->id;
    $n->company_id = $user->company_id;
    $n->title = $title;
    $n->message = $message;
    $n->type = $type;
    $n->object_id = $object_id;
    $n->save();
    $nn = new Notification();
    $nn->admin_id = $user->id;
    $nn->company_id = $user->company_id;
    $nn->title = $title;
    $nn->description = $message;
    $nn->link = '';
    // $n->type = $type;
    // $n->object_id = $object_id;
    $nn->save();
    event(new AdminNotificationEvent($n));
    // dispatch_sync(new AdminNotificationEvent($n));
    
  }
}