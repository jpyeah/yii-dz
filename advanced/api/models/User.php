<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "dz_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $access_token
 * @property string $mobile
 * @property string $password_plain
 * @property integer $updated_by
 * @property integer $created_by
 * @property string $avatar
 */
class User extends \common\models\User
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dz_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at', 'updated_by', 'created_by'], 'integer'],
            [['username', 'auth_key', 'access_token', 'password_plain'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'email', 'avatar'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 11],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['mobile'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'access_token' => 'Access Token',
            'mobile' => 'Mobile',
            'password_plain' => 'Password Plain',
            'updated_by' => 'Updated By',
            'created_by' => 'Created By',
            'avatar' => 'Avatar',
        ];
    }
}
