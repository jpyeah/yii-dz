<?php

namespace api\modules\v1\controllers;

use \yii\rest\Controller;
use EasyWeChat\Foundation\Application;
use Yii;
class WechatController extends Controller
{

    public $modelClass = '';

    public function actionIndex()
    {
       print_r('dsfas');
    }

    public function actionWxOauth(){
        $wechat=Yii::$app->wechat;
        $wechat->config->set('oauth.callback','/user/wxlogin');
        $response=$wechat->oauth->redirect();
        return $response->send();
    }

    public function actionWxOauthCallback(){

        $users =Yii::$app->wechat->oauth->user();
        $openId=$users->getId();
        $data['wx_open_id']=$users->getId();
        $data['username']=$users->getNickname();
        $data['avatar']=$users->getAvatar();
    }

    /**
     *
     * 微信用户注册登录－直接授权注册
     */
    public function actionWxlogin(){

        $users =Yii::$app->wechat->oauth->user();

        $reload_page = Yii::$app->request->get('reload_page', '/my');

        if(!Yii::$app->user->isGuest){
            return $this->redirect('/');
            //return $this->jsonSuccess('已经登录');
        }
        $openId=$users->getId();

        $data['wx_open_id']=$users->getId();
        $data['username']=$users->getNickname();
        $data['avatar']=$users->getAvatar();

        $session = Yii::$app->session;
        $session->set('wx_open_id',$users->getId());
        $session->set('username',$users->getNickname());
        $session->set('avatar',$users->getAvatar());

        $user=User::findOneByWxopenid($data['wx_open_id']);

        if($user){
            $loginmodel = new WxloginForm();
            if ($loginmodel->load($data, '') && $loginmodel->login()) {
                //合并购物车
                $user_id = Yii::$app->user->id;
                $app_cart_cookie_id = CookieUtil::getAppCartCookieId();
                if($app_cart_cookie_id){
                    Cart::combineCart($user_id, $app_cart_cookie_id);
                    //重新生成app_cart_cookie_id
                    $app_cart_cookie_id = Cart::genAppCartCookieId();
                    CookieUtil::setAppCartCookieId($app_cart_cookie_id);
                }
                return $this->redirect('/');
                //return $this->jsonSuccess(['user_id'=>Yii::$app->getUser()->getId()], '登陆成功');
            } else {
                return $this->jsonFail($loginmodel->getErrors(null), $loginmodel->errorsToOneString());
            }

        }else{

            $signupForm = new WxsignupForm();
            if($signupForm->load($data, '') && $signupForm->signup()){
                //自动登录
                $loginForm = new WxloginForm();
                $loginForm->wx_open_id = $data['wx_open_id'];
                $loginForm->username = $data['username'];
                $loginForm->avatar= $data['avatar'];
                $loginForm->login();

                //合并购物车
                $user_id = Yii::$app->user->id;
                $app_cart_cookie_id = CookieUtil::getAppCartCookieId();
                if($app_cart_cookie_id){
                    Cart::combineCart($user_id, $app_cart_cookie_id);
                    //重新生成app_cart_cookie_id
                    $app_cart_cookie_id = Cart::genAppCartCookieId();
                    CookieUtil::setAppCartCookieId($app_cart_cookie_id);
                }
                return  $this->redirect('/');
                //return $this->jsonSuccess(['user_id'=>Yii::$app->getUser()->getId()], '注册成功');
            } else {
                return $this->jsonFail($signupForm->getErrors(null), $signupForm->errorsToOneString());
            }

        }

    }

}
