<?php
/**
 * dz
 *
 * @author jpjy
 * @date 2017-10-13
 * @email zhongjipiao@126.com
 * @license The MIT License (MIT)
 */

namespace api\models;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class WxsignupForm extends Model
{
    public $wx_open_id;
    public $username;
    public $avatar; //头像

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['wx_open_id', 'filter', 'filter' => 'trim'],
            ['wx_open_id', 'required'],
            ['wx_open_id', 'string',  'max' => 32],
            ['wx_open_id', 'unique', 'targetClass' => 'common\models\User', 'message' => '微信已经注册，自动登录'],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'string', 'max' =>20],
            ['username', 'string', 'max' =>20],

            ['avatar', 'string', 'max' =>400],
            ['avatar', 'required'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
        $user->wx_open_id = $this->wx_open_id;
        $user->username   = $this->username;
        $user->avatar   = $this->avatar;
        $user->generateAuthKey();
        if($user->save()){
            return $user;
        }
        return null;
    }

    /**
     * 转换模型的错误信息数组为字符串
     * @return string
     */
    public function errorsToString()
    {
        $errors = [];
        foreach($this->errors as $attribute => $msgs){
            $msg = '';
            foreach($msgs as $msg){
                $msg .= ',';
            }
            $errors[] = $attribute . ': ' . trim($msg, ',');
        }
        return implode('; ', $errors);
    }


}
