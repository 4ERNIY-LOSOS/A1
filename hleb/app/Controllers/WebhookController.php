<?php

namespace App\Controllers;

use Hleb\Base\Controller;
use Hleb\Static\Request;
use Hleb\Static\Log;

class WebhookController extends Controller
{
    private const API_BASE_URL = 'https://api.telegram.org/bot';

    public function handleUpdate(): void
    {
        $update = Request::getJson();
        Log::info('Received update:', (array)$update);

        if (!$update) {
            Log::error('Received empty update.');
            return;
        }

        if (isset($update['message'])) {
            $this->processMessage($update['message']);
        }
    }

    private function processMessage(array $message): void
    {
        $chat_id = $message['chat']['id'];
        $text = $message['text'] ?? '';

        if ($text === '/start') {
            $this->sendStartMessage($chat_id);
        } else {
            $this->echoMessage($chat_id, $text);
        }
    }

    private function sendStartMessage(int $chat_id): void
    {
        $app_url = $_ENV['MINI_APP_BASE_URL'] ?? '';
        if (empty($app_url)) {
            Log::error('MINI_APP_BASE_URL is not set.');
            return;
        }

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Открыть приложение', 'web_app' => ['url' => $app_url]]
                ]
            ]
        ];

        $params = [
            'chat_id' => $chat_id,
            'text' => 'Добро пожаловать! Нажмите кнопку ниже, чтобы открыть приложение.',
            'reply_markup' => json_encode($keyboard)
        ];

        $this->sendRequest('sendMessage', $params);
    }

    private function echoMessage(int $chat_id, string $text): void
    {
        $response_text = 'You wrote: ' . $text;
        $params = [
            'chat_id' => $chat_id,
            'text' => $response_text,
        ];
        $this->sendRequest('sendMessage', $params);
    }

    private function sendRequest(string $method, array $params): void
    {
        $bot_token = $_ENV['TELEGRAM_BOT_TOKEN'] ?? '';
        if (empty($bot_token)) {
            Log::error('TELEGRAM_BOT_TOKEN is not set.');
            return;
        }

        $url = self::API_BASE_URL . $bot_token . '/' . $method;

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($params),
            ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === false) {
            Log::error('Telegram API request failed.', ['method' => $method, 'params' => $params]);
        } else {
            Log::info('Telegram API request successful.', ['method' => $method, 'result' => $result]);
        }
    }
}
