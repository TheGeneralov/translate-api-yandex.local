<?php

namespace Generalov\TranslateApiYandex;

class FileIAMToken extends IAMToken
{

    protected function saveToken(string $iamToken, string $expiresAt): void
    {
        $tokenFile = fopen(sys_get_temp_dir() . '/token.txt', 'w');
        fwrite($tokenFile, $iamToken);
        fclose($tokenFile);

        $expiresAtTmp = explode(".", $expiresAt);
        $expiresFile = fopen(sys_get_temp_dir() . '/expires.txt', 'w');
        fwrite($expiresFile, $expiresAtTmp[0]);
        fclose($expiresFile);
    }

    protected function getToken(): string
    {
        $fileToken = sys_get_temp_dir() . '/token.txt';
        if (!file_exists($fileToken)) {
            $this->requestToken();
        }
        return file_get_contents($fileToken);
    }

    protected function getExpiresAt(): string
    {
        $fileExpiresToken = sys_get_temp_dir() . '/expires.txt';
        if (!file_exists($fileExpiresToken)) {
            $this->requestToken();
        }
        return file_get_contents($fileExpiresToken);
    }
}
