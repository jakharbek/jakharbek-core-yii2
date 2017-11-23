<?php
namespace jakharbek\core\security\components;

use Yii;

class Security extends \yii\base\Security{
    //Generate random key
    public function random_key()
    {
        $r = mt_rand(1000000000,9999999999);
        return $r;
    }
}