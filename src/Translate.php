<?php

namespace Generalov\TranslateApiYandex;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Translate
{
    private string $url = 'https://translate.api.cloud.yandex.net/translate/v2/translate';
    private string $token;
    private string $folderId;

    /**
     *  Язык, на который переводится текст
     *  @var $targetLanguageCode
     */
    private string $targetLanguageCode = 'ru';    
    
    /**
    *  Язык, с которого переводится текст
    *  @var $sourceLanguageCode
    */
    private string $sourceLanguageCode;
    private string $format = 'PLAIN_TEXT';
    private array $body;
    private array $texts;
    private Client $client;

    public function __construct(string $token, string $folderId)
    {
        $this->client = new Client();
        $this->token = $token;
        $this->folderId = $folderId;
    }

    public function addText($text)
    {
        $this->texts[] = $text;

        return $this;
    }

    public function setFormat($format)
    {
        switch ($format) {
            case 'PLAIN_TEXT':
            case 'HTML':
                $this->format = $format;
                break;
            default:
                $this->format = 'PLAIN_TEXT';
                break;
        }

        return $this;
    }

    public function setLanguageCode($target = '', $source = '')
    {
        if($target !== '') {
            $this->targetLanguageCode = $target;
        }
        if($source !== '') {
            $this->sourceLanguageCode = $source;
        }

        return $this;
    }

    public function setBody()
    {

        $this->body = [
            "folderId" => $this->folderId,
            "format" => $this->format,
            "texts" => $this->texts,
            "targetLanguageCode" => $this->targetLanguageCode,
        ];

        return $this;
    }

    public function run()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token
        ];

        // $request = new Request('POST', 'https://translate.api.cloud.yandex.net/translate/v2/translate', $headers, json_encode($this->body));
        //$res = $this->client->sendAsync($request)->wait();
        $res = $this->client->request('POST', 'https://translate.api.cloud.yandex.net/translate/v2/translate', ['headers' => $headers, 'body' => json_encode($this->body)]);
        if ($res->getStatusCode() == 200) {

            return $res->getBody()->read(1024);
        } else {

            return false;
        }
    }
}
