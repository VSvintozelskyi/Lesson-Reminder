<?php
// Load composer
require_once '../../../vendor/autoload.php';

//TODO: paste correct bot credentials here
$bot_api_key  = YOUR_BOT_ID;
$bot_username = YOUR_BOT_USERNAME;
$USER_ID = YOUR_BOT_ADMIN_ID;

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

	// Подключение базы данных
	// $telegram->enableMySQL($mysql_credentials);

	// Добавление папки commands,
	// в которой будут лежать ваши личные комманды
	$telegram->addCommandsPath(__DIR__ . "/commands");

	// Добавление администратора бота
	$telegram->enableAdmin((int)$USER_ID);

	// Включение логов
	 // Longman\TelegramBot\TelegramLog::initUpdateLog($bot_username . '_update.log');

	// Опционально. Здесь вы можете указать кастомный объект update,
	// чтобы поймать ошибки через var_dump.
	//$telegram->setCustomInput("");

	// Основной обработчик событий
	$telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    echo $e->getMessage();
}

?>