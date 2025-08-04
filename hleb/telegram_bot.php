<?php

// Load environment variables
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$bot_token = $_ENV['TELEGRAM_BOT_TOKEN'];
$api_url = "https://api.telegram.org/bot{$bot_token}/";

$update_id = 0;

while (true) {
    $response = file_get_contents($api_url . "getUpdates?offset={$update_id}");
    $updates = json_decode($response, true);

    if (!empty($updates['result'])) {
        foreach ($updates['result'] as $update) {
            $update_id = $update['update_id'] + 1;
            $message = $update['message'];
            $chat_id = $message['chat']['id'];
            $text = $message['text'];

            if ($text === '/start') {
                $app_url = $_ENV['MINI_APP_BASE_URL']; // Use the same variable for simplicity
                $keyboard = [
                    'inline_keyboard' => [
                        [
                            ['text' => 'Открыть приложение', 'web_app' => ['url' => $app_url]]
                        ]
                    ]
                ];
                $reply_markup = json_encode($keyboard);

                $params = [
                    'chat_id' => $chat_id,
                    'text' => 'Добро пожаловать! Нажмите кнопку ниже, чтобы открыть приложение.',
                    'reply_markup' => $reply_markup
                ];
                file_get_contents($api_url . 'sendMessage?' . http_build_query($params));
            } else {
                $response_text = 'You wrote: ' . $text;
                file_get_contents($api_url . "sendMessage?chat_id={$chat_id}&text=" . urlencode($response_text));
            }
        }
    }

    sleep(1);
}
