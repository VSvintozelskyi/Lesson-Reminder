<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class GetchatidCommand extends UserCommand
{
    protected $name = 'login';                      // Your command's name
    protected $description = 'Auth for G-Calendar'; // Your command description
    protected $usage = '/login';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object
        $chat_id = $message->getChat()->getId();   // Get the current Chat ID

        $data = [                                  // Set up the new message data
            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
            'text'    => "chat_id: \n ".$chat_id,
        ];

        return Request::sendMessage($data);        // Send message!
    }
}