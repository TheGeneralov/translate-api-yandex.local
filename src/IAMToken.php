<?php

namespace Generalov\TranslateApiYandex;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Carbon\Carbon;

abstract class IAMToken
{
    private string $iamApiUrl = 'https://iam.api.cloud.yandex.net/iam/v1/tokens';
    private string $token;
    private string $expiresAt;

    private Client $client;
    private Request $request;

    public function __construct($token)
    {
        $this->token = $token;
        $this->expiresAt = $this->getExpiresAt();
        $this->client = new Client();
    }

    abstract protected function saveToken(string $iamToken, string $expiresAt): void;
    abstract protected function getExpiresAt(): string;
    abstract protected function getToken(): string;

    private function checkExpiresToken(): bool
    {
        return Carbon::parse($this->expiresAt)->timestamp > time();
    }

    protected function requestToken(): void
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $body = '{
          "yandexPassportOauthToken": "' . $this->token . '"
        }';
        $this->request = new Request('POST', $this->iamApiUrl, $headers, $body);
        $res = $this->client->sendAsync($this->request)->wait();

        $body = json_decode($res->getBody(), true);

        $this->saveToken($body['iamToken'], $body['expiresAt']);
    }

    public function get(): string
    {
        if (!$this->checkExpiresToken()) {
            $this->requestToken();
        }        
        
        return $this->getToken();
    }
}
