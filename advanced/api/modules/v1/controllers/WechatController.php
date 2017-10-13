<?php

namespace api\modules\v1\controllers;

use \yii\rest\Controller;
use EasyWeChat\Foundation\Application;
use Yii;
use common\models\User;
use api\models\WxsignupForm;
use api\models\WxLoginForm;
use yii\db\Exception as DbException;
use yii\web\IdentityInterface;


class WechatController extends Controller
{

    public $modelClass = '';

    public function actionIndex()
    {
       print_r('dsfas');
    }
    //微信授权
    public function actionWxToken(){
         $wechat = Yii::$app->wechat;
        $response = $wechat->server->serve();
        return $response->send();

    }

    public function actionWxOauth(){
        $wechat=Yii::$app->wechat;
        $wechat->config->set('oauth.callback','/v1/wechat/wx-oauth-callback');
        $response=$wechat->oauth->redirect();
        return $response->send();
    }

    public function actionWxOauthCallback(){
        $users =Yii::$app->wechat->oauth->user();
        $openId=$users->getId();
        $data['wx_open_id']=$users->getId();
        $data['username']=$users->getNickname();
        $data['avatar']=$users->getAvatar();

        $this->createUser($data);
        //return $data;
    }

    public function createUser($data){

        $user=User::findOneByWxopenid($data['wx_open_id']);

        if($user){
            $Wxloginmodel =  new WxLoginForm();
            $Wxloginmodel->wx_open_id=$data['wx_open_id'];
            if ($user = $Wxloginmodel->login()) {
                if ($user instanceof IdentityInterface) {
                    return $user->access_token;
                } else {
                    return $user->errors;
                }
            } else {
                return $Wxloginmodel->errors;
            }

        }else{

            $user = new User();
            $user->generateAuthKey();
            // $user->setPassword('123456');
            $user->username = 'jpjy123456';
            $user->avatar =  $data['avatar'];
            $user->wx_open_id = $data['wx_open_id'];
            $user->save(false);
            if($user){
                $Wxloginmodel =  new WxLoginForm();
                $Wxloginmodel->wx_open_id=$user->wx_open_id;
                if ($user = $Wxloginmodel->login()) {
                    if ($user instanceof IdentityInterface) {
                        return $user->access_token;
                    } else {
                        return $user->errors;
                    }
                } else {
                    return $Wxloginmodel->errors;
                }
            }

        }

    }

    public function actionView($id){

        $data =array();

        $data['wx_open_id'] = Yii::$app->request->get('wx_open_id');
        $data['avatar']='http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTK7qUTfB8fLaibwqELSMbwXrCdopLjCmsN7nWSQHPzWSLkrsmLBvj8HHhPibc6HK0LekzdbWROdleJg/0';
        $data['username']='jpjy1';

        $user=User::findOneByWxopenid($data['wx_open_id']);

        if($user){
            $Wxloginmodel =  new WxloginForm();
            $Wxloginmodel->wx_open_id=$data['wx_open_id'];
            if ($user = $Wxloginmodel->login()) {
                if ($user instanceof IdentityInterface) {

                    return $this->redirect('http://api.dz.com/v1/eval?token='.$user->access_token)->send();

                  //  return $user->access_token;
                } else {
                    return $user->errors;
                }
            } else {
                return $Wxloginmodel->errors;
            }

        }else{

            $user = new User();
            $user->generateAuthKey();
           // $user->setPassword('123456');
            $user->username = 'jpjy123456';
            $user->avatar =  $data['avatar'];
            $user->wx_open_id = $data['wx_open_id'];
            $user->save(false);
            if($user){
                $Wxloginmodel =  new WxloginForm();
                $Wxloginmodel->wx_open_id=$user->wx_open_id;
                if ($user = $Wxloginmodel->login()) {
                    if ($user instanceof IdentityInterface) {
                        return $this->redirect(['eval?token='.$user->access_token])->send();

                       // return $user->access_token;
                    } else {
                        return $user->errors;
                    }
                } else {
                    return $Wxloginmodel->errors;
                }
            }

        }

    }

}
