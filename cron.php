<?php

// Load composer
require_once '../../../vendor/autoload.php';
require_once __DIR__."/google.php";

//TODO: paste correct bot credentials here
$bot_api_key  = YOUR_BOT_API_KEY;
$bot_username = YOUR_BOT_USERNAME;
$USER_ID = YOUR_BOT_ADMIN_ID;

use Longman\TelegramBot\Request;

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    $client = getClient();
    $service = new \Google_Service_Calendar($client);
    //TODO: paste correct calendar ID here
    $calendarId = YOUR_CALENDAR_ID;
    $optParams = array(
      'maxResults' => 2,
      'orderBy' => 'startTime',
      'singleEvents' => true,
      'timeMin' => date('c'),
    );
    $results = $service->events->listEvents($calendarId, $optParams);
    $events = ($results->getItems());


    foreach ($events as $event) {
        $start = (new \DateTime($event->start->dateTime));
        $current = new \DateTime();
        $diff = (int) ($start->diff($current))->format("%i");
        $diffhours = (int) ($start->diff($current))->format("%h");
        $diffdays = (int) ($start->diff($current))->format("%d");
        if($diff < 18 && $diff > 12 && $diffhours == 0 && $diffdays == 0){
            $responce = "📋 Next:\n\n ⏰ ".$start->format("H:i")." 📚 ".$event->getSummary() . " \n\n 🗺️" . $event->getLocation() . (empty($event->getLocation())? " ⚠️": "");
            // $responce .= ($diff < 20) ."  ".($diff > -30) . "   ".$diff;
        
            if (file_exists(__DIR__."/groups.json")) {
                $chats = json_decode(file_get_contents(__DIR__."/groups.json"), true);
                foreach($chats as $chat){
                    Request::sendMessage([
                        'chat_id' => $chat,
                        'text'    => $responce,
                    ]);
                }
            }
            break;
        }
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Log telegram errors
    Longman\TelegramBot\TelegramLog::error($e);

    // Uncomment this to output any errors (ONLY FOR DEVELOPMENT!)
    // echo $e;
} catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
    // Uncomment this to output log initialisation errors (ONLY FOR DEVELOPMENT!)
    // echo $e;
}