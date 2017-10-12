<?php

namespace api\modules\v1\controllers;

use yii\web\Controller;
use yii\rest\ActiveController;

/**
 * Default controller for the `v1` module
 */
class DefaultController extends ActiveController
{
    public $modelClass = 'api\models\User';

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTest(){

        print_r("dsfsa");exit;
    }

    public function actionReport(){
        $app_name="ZLLBYS889J";
        $app_key='NFUurmGyEo3m3LaspgbJ8U06e4BivBLxV9yKOPNjlfGWpMsnhljBNSTD8WyrlWvK';
        $time_stamp = time();
        $nonce = mt_rand();
        $sign = calculate_sign($app_key, $time_stamp, $nonce);

        $mobile = "13218029707";
        $realname="中环";
        $url = "http://t12.renaren.com/api/user/create?app_name=".$app_name."&time_stamp=".$time_stamp."&nonce=".$nonce."&sign=".$sign."&mobile=".$mobile."&realname=".$realname;
        print_r($url);exit;
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
