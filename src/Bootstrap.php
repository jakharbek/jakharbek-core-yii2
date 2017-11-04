<?php

namespace jakharbek\core;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface{
    public function bootstrap($app)
    {
        Yii::setAlias('@jakhar/core', '@vendor/jakharbek/jakharbek-core/src');
        $app->params['yii.migrations'][] = '@vendor/jakharbek/jakharbek-core/src/migrations';
    }
}