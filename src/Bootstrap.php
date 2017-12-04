<?php

namespace jakharbek\core;

use Yii;
use yii\base\BootstrapInterface;
use jakharbek\core\assets\CoreAssets;

class Bootstrap implements BootstrapInterface{

    public static $controllers = [
        'captcha_controller' => ['captcha'],
    ];

    public static $email_template_path = '@jakhar/core/token/views/EmailTemplates/';
    public static $email_template = 'verifyEmail';

    public static $passcode_template_path = '@jakhar/core/token/views/PasscodeTemplates/';
    public static $passcode_template = 'verifyPasscode';

    const CAPTCHA_CONTROLLER = 'jakharbek\core\captcha\controllers\CaptchaController';
    const EXT_ALIAS = '@vendor/jakharbek/jakharbek-core/src';

    public static $mailer_component = "mailer";
    public static $email_from = "j.abdulatipov@light.uz";

    public function bootstrap($app)
    {
        //Set alias
        Yii::setAlias('@jakhar/core', Bootstrap::EXT_ALIAS);
        /* Register assets */
        CoreAssets::register(Yii::$app->view);

        /*  SECURITY */
            //Set Components
            Yii::$app->setComponents(
                [
                    'security'=>[
                        'class'=>'jakharbek\core\security\components\Security',
                    ],
                ]
            );
        /*
         * Регестрация переводов
         */
        $this->registerTranslations();
        /* CAPTCHA */
        $this->setController(Bootstrap::$controllers['captcha_controller'],Bootstrap::CAPTCHA_CONTROLLER);
    }

    private function setController($controller_set_to = [],$controller_path = ""){
        if(count($controller_set_to) > 0):
            foreach ($controller_set_to as $controller):
                if(preg_match("#/+#",$controller)):
                    $module = explode("/",$controller)[0];
                    $controller = explode("/",$controller)[1];
                    if(!Yii::$app->hasModule($module)){continue;}
                    Yii::$app->getModule($module)->controllerMap = array_merge(Yii::$app->getModule($module)->controllerMap, [$controller => $controller_path]);
                else:
                    Yii::$app->controllerMap = array_merge(Yii::$app->controllerMap,[$controller => $controller_path]);
                endif;
            endforeach;
        endif;
    }
    public function registerTranslations()
    {
        /*
         *  echo Yii::t("jakhar-core/captcha",'Home',[],'uz_k');
        */
        Yii::$app->i18n->translations['jakhar-core/token'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => self::EXT_ALIAS.'/token/messages',
            'fileMap' => [
                'jakhar-core/token' => 'main.php',
            ],
        ];
        Yii::$app->i18n->translations['jakhar-core/security'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => self::EXT_ALIAS.'/security/messages',
            'fileMap' => [
                'jakhar-core/security' => 'main.php',
            ],
        ];
        Yii::$app->i18n->translations['jakhar-core/captcha'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => self::EXT_ALIAS.'/captcha/messages',
            'fileMap' => [
                'jakhar-core/captcha' => 'main.php',
            ],
        ];
    }

}