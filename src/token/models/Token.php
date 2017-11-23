<?php
namespace jakharbek\core\token\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Token extends ActiveRecord{

    /*
    * @var string сценарий при генерации токена
    */
    const SCENARIO_CREATE = "create";

    /*
    * @var string сценарий при обновление токена
    */
    const SCENARIO_UPDATE = "update";

    /*
    * @var string тип токена: при авторизации
    */
    const TYPE_AUTH_USER = "auth_user";

    /*
    * @var @integer статус токена активен
    */
    const STATUS_ACTIVE = 1;

    /*
    * @var @integer статус токена не активен
    */
    const STATUS_NOACTIVE = 0;

    /*
     * @var @array свойство сценариев для Active Record
     */
    public $scenario;

    /*
     * @method @string имя текушей таблице базе данных
     */
    public static function tableName()
    {
        return "token";
    }

    /*
     * @method @string поведение
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['add_date', 'update_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_date'],
                ],
            ]
        ];
    }
    /*
     * @method @array правила
     */
    public function rules()
    {
        return [
            [['type','value','description','token','ip'],'required','on' => self::SCENARIO_CREATE],
            [['type','value','description','token','ip'],'string','max' => '255','on' => self::SCENARIO_CREATE],
        ];
    }
    public function beforeValidate()
    {
        parent::beforeValidate();
        if($this->scenario == self::SCENARIO_CREATE):
            $this->token = Yii::$app->security->generateRandomString();
            $this->ip = Yii::$app->request->getUserIP();
            if($this->type == self::TYPE_AUTH_USER):
                $this->status = self::STATUS_ACTIVE;
            endif;
            if(strlen($this->user_uid) == 0):
                $this->user_uid = Yii::$app->user->identity->uid;
                if(Yii::$app->user->isGuest){$this->user_uid = 'guest';}
            endif;
        endif;
        return true;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['type','value','description','token','ip'];
        return $scenarios;
    }
    /*
     * @method object|null возврашает токен по ключу токена
     *
     * @param string токен
     */
    public static function getByToken($token = null)
    {
        $status = 1;
        if(Token::find()->where(['token' => $token,'status' => $status])->count() == 0){return false;}
            $token_element = Token::find()->where(['token' => $token,'status' => $status])->one();
        return $token_element;
    }

    /*
     * @method boolean деактивирует токен
     *
     * @param string токен
     * @param string описание
     */
    public static function deactiveToken($token = null,$description = ""){
        if($token == null){return false;}
        if(self::getByToken($token)):
            Token::updateAll(['status' => Token::STATUS_NOACTIVE,'update_date' => time(),'description' => $description],['and',
                ['like','token',$token]
            ]);
            return true;
        endif;
        return false;
    }

    /*
     * @method boolean удалает куки токена
     *
     * @param string имя/название куки токена
     */
    public static function unsetCookiesToken($cookie_name){
        if(Yii::$app->request->cookies->has($cookie_name)):
            Yii::$app->response->cookies->remove($cookie_name);
        endif;
        return true;
    }

    /*
     * @method boolean устонавливает куки токена
     *
     * @param string токен
     * @param string имя\название куки
     */
    public static function setCookiesToken($token,$cookie_name){

        if(Yii::$app->request->cookies->has($cookie_name)):
            if(Yii::$app->request->cookies->getValue($cookie_name) == $token):
                return true;
            endif;
            Yii::$app->response->cookies->remove($cookie_name);
        endif;

        $cookies = Yii::$app->response->cookies;
        $expire = 60*60*24*365;
        $cookies->add(new \yii\web\Cookie([
            'name' => $cookie_name,
            'value' => $token,
            'expire' => time() +  $expire
        ]));

        if(!Yii::$app->request->cookies->has($cookie_name)):
            return false;
        endif;
        return true;
    }


}