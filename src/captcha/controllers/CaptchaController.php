<?php
namespace jakharbek\core\captcha\controllers;

use Yii;
use yii\web\Controller;

class CaptchaController extends Controller{

    public function actions()
    {
        return [
            //каптча
            'user' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'index' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
}
