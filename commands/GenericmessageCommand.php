<?php 

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

require __DIR__."/../../../../google/vendor/autoload.php";
require_once __DIR__."/../google.php";

/**
 * Generic command
 *
 * Gets executed for generic commands, when no other appropriate one is found.
 */
class GenericmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'generic';

    /**
     * @var string
     */
    protected $description = 'Handles generic commands or is executed by default when a command is not found';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();
        $command = $message->getCommand();

        $newchatmembers = $message->getNewChatMembers();
        if(!empty($newchatmembers))
            foreach($newchatmembers as $chatmember){
                if($chatmember->getId() == 1264638367){
                    if (file_exists(__DIR__."/../groups.json")) {
                        $chats = json_decode(file_get_contents(__DIR__."/../groups.json"), true);
                    }
                    $chats[] = $chat_id;
                    file_put_contents(__DIR__ . "/../groups.json", json_encode($chats));
                    return Request::emptyResponse();;
                }
            }

        if(($message -> getChat() -> getType() == "group") || ($message -> getChat() -> getType() == "supergroup")) return Request::emptyResponse();

        $client = getClient();
        $service = new \Google_Service_Calendar($client);
        //TODO: paste coorect calendar id here
        $calendarId = YOUR_CALENDAR_ID;
        $optParams = array(
          'maxResults' => 6,
          'orderBy' => 'startTime',
          'singleEvents' => true,
          'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        if (! empty($events))  {
            $keyboard = array("inline_keyboard" => array());
            foreach ($events as $event) {
                $date = (new \DateTime($event->start->dateTime))->format("⏰ H:i 📆 D  📚");
                $callback_data = json_encode($event->getId());
                $keyboard['inline_keyboard'][] = array(array("text" => (empty($event->getLocation()) ? "⚠️ ":"") . $date . $event->getSummary(), "callback_data"=>$callback_data));
            }
        }


        $data = [                                  // Set up the new message data
            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
            'text'    => "What subject do you want to add this info for?\n".$message->getText(),
            'reply_markup' => json_encode($keyboard),
        ];

        return Request::sendMessage($data);  
    }
}
?>