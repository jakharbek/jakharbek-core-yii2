<?php
namespace jakharbek\core\language\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;
use \yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;
use \jakharbek\menu\models\Menu;

class TabWidget extends Widget
{
    public $model;
    public $db;
    public $languages = null;
    public $url_create_translate = null;
    public $alias = 'alias';
    public $lang = null;

    public function init()
    {
        parent::init();
        if($this->languages == null)
        {
            $this->languages = Yii::$app->params['languages'];
        }
        if($this->url_create_translate == null){
            $this->url_create_translate = "/".Yii::$app->request->pathInfo;
        }
        if($this->lang == null){
            $this->lang = Yii::$app->language;
        }

    }

    public function run()
    {
        if($this->model->{$this->alias})
        {
            echo '<ul class="nav nav-tabs">';
            if(count( ($languages = $this->languages)) > 1):
                foreach ($languages as $lang=>$language):
                    echo "<li class='".($lang == $this->model->lang ? 'active':'')."'>";
                        $url_create_translate = Url::to([$this->url_create_translate,'lang' => $lang,'alias' => $this->model->alias]);
                        echo "<a href='".$url_create_translate."'>".$languages[$lang]."</a>";
                    echo "</li>";
                endforeach;
            endif;
            echo '</ul>';
        }
    }
}