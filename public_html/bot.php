<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

require('../vendor/autoload.php'); //Подключаем библиотеку
use Telegram\Bot\Api;

$telegram = new Api('');
$result = $telegram -> getWebhookUpdates();
$text = $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$keyboard = [["Последние статьи"],["Картинка"],["Гифка"]]; //Клавиатура

if($text){
    $reply = "Добро пожаловать, $name";
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
}
?>
