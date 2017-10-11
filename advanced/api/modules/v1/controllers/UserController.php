<?php

namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use api\models\User;
class UserController extends ActiveController
{
    public $modelClass = 'api\models\User';
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView($id)
    {
        return User::findOne($id);
    }

}
