<?php

namespace api\modules\v1\controllers;
use api\models\User;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\data\ActiveDataProvider;
use \yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\IdentityInterface;
use common\models\UserTest;

class EvalController extends \yii\rest\Controller
{
    public $modelClass = '';

    public function behaviors()
    {
        return ArrayHelper::merge (parent::behaviors(), [
            'authenticator' => [
                'class' => QueryParamAuth::className(),
                'tokenParam' => 'token',
                'optional' => [
                    'create',
                ],
            ]
        ] );
    }

    public function actionIndex(){

        $token= Yii::$app->request->get('token');

        $user = User::findIdentityByAccessToken($token);

        $test_user = UserTest::findOne(['user_id'=>$user->id]);

        if($test_user){

            if($test_user->test_code){

                $url = $this->createurl($test_user->test_code,$finishurl,$closeurl);

                return $this->redirect($url)->send();

            }else{
                $res = $this->createusercode($test_user->test_user_id);
                if($res->success){
                    $test_user->test_code=$res->data->code;
                    $test_user->save(false);

                    $url = $this->createurl($res->data->code,$finishurl,$closeurl);

                    return $this->redirect($url)->send();
                }else{
                    return $res;
                }
            }

        }else{

            echo  $this->render('index');

        }


        //return $this->redirect(['admin/index','id'=>$id])->send();
    }

    public function actionCreate(){

        echo "2434554s";
    }

    public function actionView($id){

        $token = Yii::$app->request->get('token');

        $user = User::findIdentityByAccessToken($token);

        $test_user = UserTest::findOne(['user_id'=>$user->id]);

        $finishurl = 'http://dzapi.bibicars.com/v1/eval';
        $closeurl = 'http://dzapi.bibicars.com/v1/eval';

        if($test_user){

            if($test_user->test_code){

                $url = $this->createurl($test_user->test_code,$finishurl,$closeurl);

                return $this->redirect($url)->send();

            }else{

                $res = $this->createusercode($test_user->test_user_id);

                if($res->success){
                    $test_user->test_code=$res->data->code;
                    $test_user->save(false);

                    $url = $this->createurl($res->data->code,$finishurl,$closeurl);

                    return $this->redirect($url)->send();
                }else{
                    return $res;
                }
            }

        }else{
            $mobile = "13218029708";
            $realname = "测试中环";
            $res = $this->createuser($mobile,$realname);
            if($res->success){
                $UserTestM = new UserTest();
                $UserTestM->test_user_id = $res->data->userid;
                $UserTestM->test_realname = $realname;
                $UserTestM->user_id = $user->id;
                $UserTestM->test_mobile = $mobile;
                $UserTestM->save(false);
                $result = $this->createusercode($test_user->test_user_id);
                if($result->success){

                    $test_user->test_code=$result->data->code;
                    $test_user->save(false);

                    $url = $this->createurl($result->data->code,$finishurl,$closeurl);

                    return $this->redirect($url)->send();

                }else{
                    return $result;
                }
            }else{
                return $res;
            }
        }


//跳转测评
//        $app_name="ZLLBYS889J";
//        $app_key='NFUurmGyEo3m3LaspgbJ8U06e4BivBLxV9yKOPNjlfGWpMsnhljBNSTD8WyrlWvK';
//        $time_stamp = time();
//        $nonce = mt_rand();
//        $sign = $this->calculate_sign($app_key, $time_stamp, $nonce);
//        $code = "URZ9W91PPJ";
//        $finishurl="http://baidu.com";
//        $closeurl = "http://baidu.com";
//
//        $mobile = "13218029707";
//        $realname="中环";
//       // $url = "http://t12.renaren.com/api/user/create?app_name=".$app_name."&time_stamp=".$time_stamp."&nonce=".$nonce."&sign=".$sign."&mobile=".$mobile."&realname=".$realname;
//        $url = "http://t12.renaren.com/test/do?app_name=".$app_name."&code=".$code."&time_stamp=".$time_stamp."&nonce=".$nonce."&sign=".$sign."&cb=redirect&cbfinish=".$finishurl."&cbclose=".$closeurl;
//        return $this->redirect($url)->send();
    }



    public function createuser($mobile,$realname){

        $app_name="ZLLBYS889J";
        $app_key='NFUurmGyEo3m3LaspgbJ8U06e4BivBLxV9yKOPNjlfGWpMsnhljBNSTD8WyrlWvK';
        $time_stamp = time();
        $nonce = mt_rand();
        $sign = $this->calculate_sign($app_key, $time_stamp, $nonce);

        $url = "http://t12.renaren.com/api/user/create?app_name=".$app_name."&time_stamp=".$time_stamp."&nonce=".$nonce."&sign=".$sign."&mobile=".$mobile."&realname=".$realname;

        $res = $this->curl($url);

        $res = json_decode($res);

        return $res;

    }

    public function createusercode($user_id){

        $app_name="ZLLBYS889J";
        $app_key='NFUurmGyEo3m3LaspgbJ8U06e4BivBLxV9yKOPNjlfGWpMsnhljBNSTD8WyrlWvK';
        $time_stamp = time();
        $nonce = mt_rand();
        $sign = $this->calculate_sign($app_key, $time_stamp, $nonce);

        $url = "http://t12.renaren.com/api/code/create?app_name=".$app_name."&nonce=".$nonce."&time_stamp=".$time_stamp."&userid=".$user_id."&sign=".$sign;

        $res = $this->curl($url);

        $res = json_decode($res);

        return $res;

    }
    //生成测评url
    public function createurl($code,$finishurl,$closeurl){

        //跳转测评
        $app_name="ZLLBYS889J";
        $app_key='NFUurmGyEo3m3LaspgbJ8U06e4BivBLxV9yKOPNjlfGWpMsnhljBNSTD8WyrlWvK';
        $time_stamp = time();
        $nonce = mt_rand();
        $sign = $this->calculate_sign($app_key, $time_stamp, $nonce);

        $url = "http://t12.renaren.com/test/do?app_name=".$app_name."&code=".$code."&time_stamp=".$time_stamp."&nonce=".$nonce."&sign=".$sign."&cb=redirect&cbfinish=".$finishurl."&cbclose=".$closeurl;

        return $url;

    }

    public function curl($url){

        $ch = curl_init();
        $timeout = 5;
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }
    /**
     * 返回参数加密签名Sign
     *
     * @param string $app_key,接入者密钥
     * @param string $time_stamp,时间戳.自从 Unix 纪元（格林威治时间 1970 年 1 月 1 日 00:00:00）到当前时间的秒数
     * @param string $nonce,随机数.9位随机整数
     * @return  string
     */
    function calculate_sign($app_key, $time_stamp, $nonce) {
        $tmpStr = $app_key.$time_stamp.$nonce;
        $tmpStr = sha1($tmpStr);
        return $tmpStr;
    }

}
