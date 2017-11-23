<?php

namespace jakharbek\core\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class CoreAssets extends AssetBundle
{
    public $sourcePath = '@jakhar/core/web/';

    public $css = [
        'css/main.css',
    ];

    public $js = [
        'js/main.js',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_END];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
