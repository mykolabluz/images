<?php

namespace frontend\components;

use yii\base\BootstrapInterface;

class LanguageSelector implements BootstrapInterface
{
    
    public $supportedLanguage = ['en-US', 'ru-RU'];
    public function bootstrap($app)
    {
        $cookieLanguage = $app->request->cookies['language'];
        if (isset($cookieLanguage) && in_array($cookieLanguage, $this->supportedLanguage)){
            $app->language = $app->request->cookies['language'];
        }
    }
}
