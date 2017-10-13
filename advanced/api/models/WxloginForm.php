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
 * Login form
 */
class WxLoginForm extends Model
{
    public $wx_open_id;
    public $username;
    public $avatar;

    public $_user;

    const GET_API_TOKEN = 'generate_api_token';

    public function init ()
    {
        parent::init();
        $this->on(self::GET_API_TOKEN, [$this, 'onGenerateApiToken']);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['wx_open_id'], 'required'],
            ['wx_open_id', 'validateWxopenid'],

            // rememberMe must be a boolean value

        ];
    }

    /**
     * 自定义的密码认证方法
     */
    public function validateWxopenid($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_user = $this->getUser();
            if (!$this->_user) {
                $this->addError($attribute, '用户名或密码错误.');
            }
        }
    }
    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $this->trigger(self::GET_API_TOKEN);
            return $this->_user;
        } else {
            return null;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
          
           $this->_user = User::findOneByWxopenid($this->wx_open_id);
            
        }
        return $this->_user;
    }

    /**
     * 登录校验成功后，为用户生成新的token
     * 如果token失效，则重新生成token
     */
    public function onGenerateApiToken ()
    {
        if (!User::apiTokenIsValid($this->_user->access_token)) {
            $this->_user->generateApiToken();
            $this->_user->save(false);
        }
    }
}
