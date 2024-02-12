# Translate API V2 Yandex Cloud 

```php
$yaToken = '';
$folderId = '';

$fileIAMToken = new FileIAMToken($yaToken);
$translate = new TranslateYandex($yaToken, $folderId, $fileIAMToken->get());

$result = $translate->translate->setLanguageCode('en', 'ru')->addText('тест')->addText('тест2')->setFormat('HTML')->setBody()->run();
```