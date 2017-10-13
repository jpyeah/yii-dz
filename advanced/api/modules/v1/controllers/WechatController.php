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
use EasyWeChat\Message\Text;


class WechatController extends Controller
{

    public $modelClass = '';

    public function actionIndex()
    {
       print_r('dsfas');
    }

    public function actionWechat(){

        $server =Yii::$app->wechat->server;

        $server->setMessageHandler(function($message){

            switch ($message->MsgType) {
                case 'event':
                    switch ($message->Event) {
                        case 'subscribe'://未关注过的扫码

                                return new Text(['content' => "欢迎关注点孵教育科技，希望我们能与您携手-趁现在，遇见未来..."]);
                            break;
                        case 'SCAN'://已关注过的扫码

                                return new Text(['content' => "欢迎关注点孵教育科技，希望我们能与您携手-趁现在，遇见未来..."]);

                            break;
                        default:
                            return new Text(['content' => "欢迎关注点孵教育科技，希望我们能与您携手-趁现在，遇见未来..."]);
                            break;
                    }
                    break;
                case 'text':
                    return new Text(['content' => "欢迎关注点孵教育科技，希望我们能与您携手-趁现在，遇见未来..."]);
                    break;
                case 'image':
                    return "图片";
                    break;
                case 'voice':
                    return "声音";
                    break;
                case 'video':
                    # 视频消息...
                    break;
                case 'location':
                    return "位置";
                    break;
                case 'link':
                    # 链接消息...
                    break;
                // ... 其它消息
                default:
                    return new Text(['content' => "欢迎关注点孵教育科技，希望我们能与您携手-趁现在，遇见未来..."]);
                    break;
            }

        });

        $response = $server->serve();

        return $response->send();


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

    public function actionWxOauthDetail(){

        $wechat=Yii::$app->wechat;
        $wechat->config->set('oauth.callback','/v1/eval/detail');
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

                    return $this->redirect('http://dzapi.bibicars.com/v1/eval?token='.$user->access_token)->send();

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
            $user->username =  $data['username'];
            $user->avatar =  $data['avatar'];
            $user->wx_open_id = $data['wx_open_id'];
            $user->save(false);
            if($user){
                $Wxloginmodel =  new WxLoginForm();
                $Wxloginmodel->wx_open_id=$user->wx_open_id;
                if ($user = $Wxloginmodel->login()) {
                    if ($user instanceof IdentityInterface) {
                        return $this->redirect('http://dzapi.bibicars.com/v1/eval?token='.$user->access_token)->send();

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
                        return $this->redirect('http://api.dz.com/v1/eval?token='.$user->access_token)->send();

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
