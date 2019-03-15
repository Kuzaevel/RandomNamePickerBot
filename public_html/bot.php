<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../vendor/autoload.php'); //Подключаем библиотеку
use Telegram\Bot\Api;

$telegram = new Api('');
$result = $telegram -> getWebhookUpdates();
$text = $_GET['text']?$_GET['text'] : $result["message"]["text"]; //Текст сообщения
$chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
$name = $result["message"]["from"]["username"]; //Юзернейм пользователя
$keyboard_start = [["Списко"],["Выбор из списка"]]; //Клавиатура

$arr = [ "bv1", "имя2","имя3","name", "surname"];

if($text){

    switch ($text) {

        case "rnd":
            $reply = "";

//            var_dump($arr[array_rand($arr)]);
//
            foreach($arr as $val) {
                //echo $val;
                $reply .= $val . "\r\n";
            }

            foreach ($arr as $k=>$v) {
                echo $v;
                $reply .= $v."\r\n";
            }

        break;

        case "/start":
            $reply_markup = $telegram->replyKeyboardMarkup([
                'keyboard' => $keyboard_start,
                'resize_keyboard' => true,
                'one_time_keyboard' => false
            ]);
            $reply = "Добро пожаловать!!!";
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
            break;
        case "Списко":
            foreach($arr as $val) {
                $reply .= $val . ("\r\n");
            }
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);

            //$reply_markup = $telegram->
            break;
        case "Выбор из списка":
            $reply = $arr[array_rand($arr)];
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
            break;
    }
}
