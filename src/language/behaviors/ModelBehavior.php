<?php
namespace jakharbek\core\language\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Model;

class ModelBehavior extends Behavior{

    public $lang = null;
    public $attribute_lang = 'lang';
    public function init(){
        if($this->lang == null){
            $this->lang = Yii::$app->language;
        }
    }
    public function events()
    {
        return [Model::EVENT_BEFORE_VALIDATE => 'beforeValidate'];
    }
    public function beforeValidate(){
        if($this->owner->{$this->attribute_lang} == null):
            $this->owner->{$this->attribute_lang} = $this->lang;
        endif;
    }
}