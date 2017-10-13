<?php

namespace api\modules\v1\controllers;
use api\models\User;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\data\ActiveDataProvider;
use \yii\helpers\Json;
use api\models\LoginForm;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\IdentityInterface;


class UserController extends Controller
{
    public $modelClass = 'common\models\user';

    public function behaviors()
    {
        return ArrayHelper::merge (parent::behaviors(), [
            'authenticator' => [
                'class' => QueryParamAuth::className(),
                'tokenParam' => 'token',
                'optional' => [
                    'login',
                    'signup-test',
                ],
            ]
        ] );
    }
    /**
     * 判断用户登录信息，并返回结果。
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            $data=array(
                'code'=>100,
                'message'=>'用户未登录',
                'data'=>'',
            );
        }else{
            $data=array(
                'code'=>200,
                'message'=>'用户已经登录',
                'data'=>array(
                    'user_id'=>Yii::$app->user->id,
                    'user_name'=>isset(\Yii::$app->user->identity->username) ? \Yii::$app->user->identity->username : '',
                ),
            );
        }
        echo json_encode($data);exit;
    }

    /**
     * 添加测试用户
     */
    public function actionSignupTest()
    {
        $user = new User();
        $user->generateAuthKey();
        $user->setPassword('123456');
        $user->username = 'jpyeah';
        $user->email = '1116@111.com';
        $user->save(false);

        return [
            'code' => 0
        ];
    }

    public function actionLogin()
    {
        $model = new LoginForm;
        $model->setAttributes(Yii::$app->request->post());
        if ($user = $model->login()) {
            if ($user instanceof IdentityInterface) {
                return $user->access_token;
            } else {
                return $user->errors;
            }
        } else {
            return $model->errors;
        }
    }

    public function actionLoginByWx(){



    }

    /**
     * 获取用户信息
     */
    public function actionUserProfile ($token)
    {
        // 到这一步，token都认为是有效的了
        // 下面只需要实现业务逻辑即可，下面仅仅作为案例，比如你可能需要关联其他表获取用户信息等等
        $user = User::findIdentityByAccessToken($token);
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
        ];
    }

    public function actionView($id)
    {
        if(Yii::$app->user->isGuest){
            $data=array(
                'code'=>100,
                'message'=>'用户未登录',
                'data'=>'',
            );
        }else{
            $data=array(
                'code'=>200,
                'message'=>'用户已经登录',
                'data'=>array(
                    'user_id'=>Yii::$app->user->id,
                    'user_name'=>isset(\Yii::$app->user->identity->username) ? \Yii::$app->user->identity->username : '',
                ),
            );
        }
        echo json_encode($data);exit;
        return User::findOne($id);
    }

}
