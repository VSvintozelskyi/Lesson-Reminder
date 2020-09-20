<?php

/**
 * This file is part of the PHP Telegram Bot example-bot package.
 * https://github.com/php-telegram-bot/example-bot/
 *
 * (c) PHP Telegram Bot Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Callback query command
 *
 * This command handles all callback queries sent via inline keyboard buttons.
 *
 * @see InlinekeyboardCommand.php
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

require __DIR__."/../../../../google/vendor/autoload.php";
require_once __DIR__."/../google.php";

class CallbackqueryCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Handle the callback query';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws \Exception
     */
    public function execute(): ServerResponse
    {
        // Callback query data can be fetched and handled accordingly.
        $callback_query = $this->getCallbackQuery();
        $callback_data  = json_decode($callback_query->getData());

        $client = getClient();
        $service = new \Google_Service_Calendar($client);
        //TODO: paste coorect calendar id here
        $calendarId = YOUR_CALENDAR_ID;
        // throw new \Exception($callback_data);
        $event = $service->events->get($calendarId, $callback_data);
        $event->setLocation(substr($callback_query->getMessage()->getText(),47));
        $event = $service->events->update($calendarId, $callback_data, $event);

        Request::editMessageText([
                'chat_id'    => $callback_query->getMessage()->getChat()->getId(),
                'message_id' => $callback_query->getMessage()->getMessageId(),
                'text'       => $callback_query->getMessage()->getText() . "\n\n ✅ success! \n Added for ". $event->getSummary(),
            ]);

        return $callback_query->answer([
            'text'       => $event->getLocation(),
            'show_alert' => false, // Randomly show (or not) as an alert.
            'cache_time' => 5,
        ]);
    }
}