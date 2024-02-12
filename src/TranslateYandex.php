<?php

namespace Generalov\TranslateApiYandex;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class TranslateYandex
{
    private string $token;
    private string $folderId;
    private string $iamToken;
    private Client $client;
    private Request $request;
    public Translate $translate;

    public function __construct(string $token, string $folderId, $iamToken)
    {
        $this->token = $token;
        $this->folderId = $folderId;
        $this->iamToken = $iamToken;
        $this->client = new Client();
        $this->translate = new Translate($this->iamToken, $folderId);
    }    
}
