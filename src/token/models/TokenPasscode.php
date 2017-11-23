<?php
namespace jakharbek\core\token\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use \jakharbek\user\models\User;
use jakharbek\core\Bootstrap;

class TokenPasscode extends Token{

    /*
     * @var string тип токена: для потверждение электронный почты
     */
    const TYPE_PASSWORD_RESET = "verify_passcode_reset";
    /*
    * @var @integer статус токена проверенный
    */
    const STATUS_WAIT_VERIFY = 2;
    const STATUS_VERIFY = 3;
    const STATUS_DELETE_TOKEN = 4;

    public function beforeValidate()
    {
        $validate = parent::beforeValidate();
        if($this->scenario == self::SCENARIO_CREATE):
            $this->token = Yii::$app->security->generateRandomString();
            $this->ip = Yii::$app->request->getUserIP();
            $this->status = self::STATUS_WAIT_VERIFY;
        endif;
        $validate = $validate + true;
        return $validate;
    }
    public static function resetPassword($email = null,$verify_url = null,$delete_token_url = null){
        if($email == null){return false;}
        if(!is_array($verify_url)){return false;}
        if(!is_array($delete_token_url)){return false;}
        $token = new self(['scenario' => self::SCENARIO_CREATE]);
        $new_password = Yii::$app->security->random_key();
        $token->description = "New Password Create";
        $token->type = self::TYPE_PASSWORD_RESET;
        $token->value = $new_password;
        if(User::hasByEmail($email)):
            $user = User::getByEmail($email);
            $token->user_uid = $user->uid;
        endif;
        if($token->save()){
            self::sendEmailVerifyLink($email,$new_password,$token,$verify_url,$delete_token_url);
            return $token;
        }
        return false;
    }
    public static function sendEmailVerifyLink($email = null,$new_password = null,$token = null,$verify_url = null,$delete_token_url = null){
        if($email == null){return false;}
        if($new_password == null){return false;}
        if(!is_array($verify_url)){return false;}
        if(!is_array($delete_token_url)){return false;}
        $verify_link = \yii\Helpers\Url::to([$verify_url['link'],$verify_url['param'] => $token->token],true);
        $delete_token_link = \yii\Helpers\Url::to([$delete_token_url['link'],$delete_token_url['param'] => $token->token],true);
        $body = Yii::$app->view->render(Bootstrap::$passcode_template_path.Bootstrap::$passcode_template,compact(['verify_link','delete_token_link','token','email','new_password']));

        Yii::$app->{Bootstrap::$mailer_component}->compose()
            ->setFrom(Bootstrap::$email_from)
            ->setTo($email)
            ->setSubject(Yii::t('jakhar-core/token','New Password Create'))
            ->setHtmlBody($body)
            ->send();
    }
    public static function verifyPasscode($token = null){
        if($data = self::find()->andWhere(['token' => $token])->andWhere(['status' => self::STATUS_WAIT_VERIFY])->andWhere(['type' => self::TYPE_PASSWORD_RESET])->one()):
            if(self::updateAll(['status' => self::STATUS_VERIFY,'update_date' => time(),'description' => 'verified email'],['token' => $token])):
                $user = User::getByUid($data->user_uid);
                if($user):
                    $user->setNewPasscode($data->value);
                    $user->save();
                else:
                    return false;
                endif;
                return $data;
            endif;
        endif;
        return false;
    }
    public static function verifyPasscodeDelete($token = null){
        if($data = self::find()->andWhere(['token' => $token])->andWhere(['status' => self::STATUS_WAIT_VERIFY])->andWhere(['type' => self::TYPE_PASSWORD_RESET])->one()):
            if(self::updateAll(['status' => self::STATUS_DELETE_TOKEN,'update_date' => time(),'description' => 'delete token'],['token' => $token])):
                return $data;
            endif;
        endif;
        return false;
    }
}