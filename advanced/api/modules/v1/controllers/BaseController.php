<?php

namespace api\modules\v1\controllers;

/**
 *接口登陆验证
 * @author 爱博
 * 1.0
 *
 */

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\filters\RateLimiter;
use yii\rest\Controller;
use Yii;

class BaseController extends Controller
{
    public $modelClass = 'common\models\user';

    public $post = null;
    public $get = null;
    public $user = null;
    public $userId = null;

    public function init()
    {
        parent::init();

        Yii::$app->user->enableSession = false;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                //     HttpBasicAuth::className(),
                //     HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],
        ];

        return $behaviors;
    }


//    public function beforeAction($action)
//    {
//        parent::beforeAction($action);
//
//        $this->post = yii::$app->request->post();
//        $this->get = yii::$app->request->get();
//        $this->user = yii::$app->user->identity;
//        $this->userId = Yii::$app->user->id;
//
//        return $action;
//    }


}
