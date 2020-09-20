<?php
// Load composer
require '../../../vendor/autoload.php';

//TODO: paste correct bot credentials here
$bot_api_key  = YOUR_BOT_ID;
$bot_username = YOUR_BOT_USERNAME;
$hook_url     = YOUR_WEBHOOK_URL;

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}

?>