<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

require __DIR__."/../../../../google/vendor/autoload.php";
require_once __DIR__."/../google.php";

class ScheduleCommand extends UserCommand
{
    protected $name = 'schedule';                      // Your command's name
    protected $description = 'Get schedule for next (current) day'; // Your command description
    protected $usage = '/schedule';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object
        $chat_id = $message->getChat()->getId();   // Get the current Chat ID
        
        $client = getClient();
        $service = new \Google_Service_Calendar($client);

        //TODO: paste coorect calendar id here
        $calendarId = YOUR_CALENDAR_ID;
        $optParams = array(
          'maxResults' => 4,
          'orderBy' => 'startTime',
          'singleEvents' => true,
          'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        if (empty($events)) {
            $responce = "No upcoming events found.\n";
        } else {
            $responce = "Schedule for 📆 ";
            $warning = false;
            foreach ($events as $event) {
                if(empty($start)){
                    $start = (new \DateTime($event->start->dateTime))->format('m/d/Y');
                    $responce .= (new \DateTime($event->start->dateTime))->format("D:\n");
                }
                $dt = (new \DateTime($event->start->dateTime));
                if($start != $dt->format('m/d/Y')) break;
                $time = $dt->format('H:i');
                $emoji = ($time == "08:40" ? json_decode('"\u0031\uFE0F\u20E3"') : ($time == "10:35" ? json_decode('"\u0032\uFE0F\u20E3"') : ($time == "12:20" ? json_decode('"\u0033\uFE0F\u20E3 "'): "?")));
                $responce .= ( "   ".$emoji . "  ⏰ ". $dt->format("H:i") . "  📚 " . $event->getSummary() . "  🗺️ " . (empty($event->getLocation()) ? ⚠️ : $event->getLocation()) . "\n\n");
                if(empty($event->getLocation())) $warning = true;
            }
            if($warning)
                $responce .= "\n ⚠️ Some event`s dont have any info! ⚠️";
        }

        $data = [                                  // Set up the new message data
            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
            'text'    => $responce,
        ];

        return Request::sendMessage($data);        // Send message!
    }
}