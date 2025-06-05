<?php

namespace App\Broadcasting;

use App\Models\User;

class NotifChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  mixed $user
     * @return array|bool
     */
    public function join($user, $worksheetId, $user_id)
    {
        return true;
    }
}
