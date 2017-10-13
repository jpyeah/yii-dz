<?php

namespace common\models;

use Yii;


/**
 * This is the model class for table "dz_user_test".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $test_user_id
 * @property string $test_mobile
 * @property string $test_code
 * @property string $test_realname
 * @property string $created_at
 * @property string $updated_at
 */
class UserTest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dz_user_test';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'test_user_id'], 'integer'],
            [['test_mobile', 'test_code', 'test_realname', 'created_at', 'updated_at'], 'string', 'max' => 35],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'test_user_id' => 'Test User ID',
            'test_mobile' => 'Test Mobile',
            'test_code' => 'Test Code',
            'test_realname' => 'Test Realname',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


}
