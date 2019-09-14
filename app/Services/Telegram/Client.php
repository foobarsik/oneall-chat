<?php

namespace App\Services\Telegram;

use App\Contracts\Messenger;
use App\Exceptions\InvalidAuthTokenException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Client for Telegram API
 *
 * @see https://core.telegram.org/bots/api
 */
class Client implements Messenger
{
    protected const BASE_URI = 'https://api.telegram.org/bot';
    protected const STATUS_UNAUTHORIZED = 401;

    protected $token;

    /**
     * @param string $token authentication token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set webhook url. This webhook will be used for receiving callbacks and user messages from Telegram.
     * Only URLs with valid and official SSL certificate from a trusted CA is allowed.
     *
     * @param string $url
     * @return bool
     * @throws InvalidAuthTokenException
     * @throws BadRequestHttpException
     */
    public function setWebhook(string $url)
    {
        return $this->sendRequest('setWebhook', ['url' => $url]);
    }

    /**
     * Delete webhook.
     *
     * @return bool
     * @throws InvalidAuthTokenException
     * @throws BadRequestHttpException
     */
    public function deleteWebhook()
    {
        return $this->sendRequest('deleteWebhook');
    }

    /**
     * Fetch basic information about the bot.
     *
     * @return array
     * @throws InvalidAuthTokenException
     * @throws BadRequestHttpException
     */
    public function getProfile()
    {
        return $this->sendRequest('getMe');
    }

    /**
     * Send text message to Telegram user.
     *
     * @param int $chatId
     * @param string $text
     * @return array
     * @throws InvalidAuthTokenException
     * @throws BadRequestHttpException
     */
    public function sendMessage($chatId, $text)
    {
        return $this->sendRequest('sendMessage', ['chat_id' => $chatId, 'text' => $text]);
    }

    /**
     * Send an API request.
     *
     * @param string $endpoint
     * @param array $params
     * @param bool $multipart
     * @return mixed
     * @throws InvalidAuthTokenException
     * @throws BadRequestHttpException
     */
    protected function sendRequest($endpoint, $params = [], $multipart = false)
    {
        $endPointUrl = self::BASE_URI . $this->token . '/' . $endpoint;
        $contentType = $multipart ? 'multipart' : 'form_params';
        try {
            $http = new HttpClient();
            $result = $http->post($endPointUrl, [$contentType => $params]);
            $response = json_decode($result->getBody(), true);
            if ($response['ok'] === false) {
                $description = $response['description'] ?: 'no description given';
                $statusCode = $response['error_code'] ?: '';
                throw new BadRequestHttpException("Telegram returned error `{$statusCode} - {$description}`");
            }
            return $response['result'];
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            if ($statusCode === self::STATUS_UNAUTHORIZED) {
                throw new InvalidAuthTokenException('Provided token is invalid');
            }
            $description = 'no description given';
            if ($result = json_decode($e->getResponse()->getBody())) {
                $description = $result->description ?: $description;
            }
            throw new BadRequestHttpException("Telegram responded with an error `{$statusCode} - {$description}`");
        }
    }
}
