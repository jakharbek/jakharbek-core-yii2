<?php
namespace jakharbek\core\language\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;
use \yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;
use \jakharbek\menu\models\Menu;

class AliasFieldWidget extends Widget
{
    public $model;
    public $form;
    public $languages = null;
    public $alias = 'alias';
    public $lang = null;

    public function init()
    {
        parent::init();
        if($this->languages == null)
        {
            $this->languages = Yii::$app->params['languages'];
        }
        if($this->lang == null){
            $this->lang = Yii::$app->language;
        }
    }

    public function run()
    {
        echo $this->form->field($this->model,$this->{$this->alias})->textInput(['readonly' => !empty($this->model->{$this->alias})]);
    }
}