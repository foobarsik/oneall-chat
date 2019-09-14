<?php

namespace App\Services\Viber;

use App\Contracts\Messenger;
use App\Exceptions\InvalidAuthTokenException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Client for Viber API
 *
 * @see https://developers.viber.com/api/rest-bot-api/index.html
 */
class Client implements Messenger
{
    protected const BASE_URI = 'https://chatapi.viber.com/pa/';
    protected const STATUS_OK = 0;
    protected const STATUS_INVALID_TOKEN = 2;

    protected $token;

    /**
     * @param string $token authentication token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set webhook url. This webhook will be used for receiving callbacks and user messages from Viber.
     * Only URLs with valid and official SSL certificate from a trusted CA is allowed.
     *
     * @param string $url
     * @return array
     * @throws InvalidAuthTokenException
     * @throws BadRequestHttpException
     */
    public function setWebhook(string $url)
    {
        return $this->sendRequest('set_webhook',
            [
                'url' => $url,
                'event_types' => [
                    'message',
                    'delivered',
                    'seen',
                    'failed',
                    'subscribed',
                    'unsubscribed'
                ],
                'send_name' => true,
                'send_photo' => true
            ]
        );
    }

    /**
     * Delete webhook.
     *
     * @return array
     * @throws InvalidAuthTokenException
     * @throws BadRequestHttpException
     */
    public function deleteWebhook()
    {
        return $this->sendRequest('set_webhook', ['url' => '']);
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
        return $this->sendRequest('get_account_info');
    }

    /**
     * Send text message to Viber user.
     *
     * @param string $receiverId
     * @param string $text
     * @return array
     * @throws InvalidAuthTokenException
     * @throws BadRequestHttpException
     */
    public function sendMessage($receiverId, $text)
    {
        $params = [
            'receiver' => $receiverId,
            'type' => 'text',
            'text' => $text
        ];
        return $this->sendRequest('send_message', $params);
    }

    /**
     * Send an API request.
     *
     * @param string $endpoint
     * @param array $params
     * @return array
     * @throws InvalidAuthTokenException
     * @throws BadRequestHttpException
     */
    protected function sendRequest($endpoint, $params = [])
    {
        try {
            $http = new HttpClient(['headers' => ['X-Viber-Auth-Token' => $this->token]]);
            $response = $http->post(self::BASE_URI . $endpoint, ['body' => $params ? json_encode($params) : '']);
            $result = json_decode($response->getBody(), true);
            $status = $result['status'];
            if ($status === self::STATUS_OK) {
                return $result;
            }
            if ($status === self::STATUS_INVALID_TOKEN) {
                throw new InvalidAuthTokenException('Provided token is invalid');
            }
            throw new BadRequestHttpException("Viber responded with an error `{$status} - {$result['status_message']}`");
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $description = 'no description given';
            if ($result = json_decode($e->getResponse()->getBody())) {
                $description = $result->description ?: $description;
            }
            throw new BadRequestHttpException("Viber responded with an error `{$statusCode} - {$description}`");
        }
    }
}
