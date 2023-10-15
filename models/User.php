<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use \yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use \app\models\UserSettings;
use yii\data\ActiveDataProvider;
use app\models\RelateClientToUser;

class User extends ActiveRecord implements IdentityInterface
{
    public $password_confirm;
    public $role;
    public $client_id;
    public $cachedId;

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_UPDATE = 'update';

    public function rules()
    {
        return [
            [[
                'full_name', 
                'username', 
                'email', 
                'password', 
                'password_confirm', 
                'is_active', 
                'role',
                'client_id'
            ], 'safe'],
            [
                ['full_name', 'username', 'email', 'password', 'password_confirm', 'role'], 
                'required', 
                'on' => [self::SCENARIO_REGISTER, self::SCENARIO_UPDATE]
            ],
            [['username', 'password'], 'required', 'on' => [self::SCENARIO_LOGIN, self::SCENARIO_UPDATE]],
            [
                'password_confirm', 
                'compare', 
                'compareAttribute' => 'password', 
                'message' => Yii::t('app/models/user', 'Passwords do not match')
            ],
            ['email', 'email'],
            ['username', 'unique'],
            ['client_id', 'integer'],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            $this->setPassword($this->password);
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $this->assignRole();
            $userSettings = new UserSettings(['user_id' => $this->id]);
            $userSettings->save();
            
            $this->createClient();
            return true;
        }
        return false;
    }


    public function beforeDelete()
    {
        $this->cachedId = $this->id;
        return parent::beforeDelete();
    }

    public function afterDelete()
    {
        $auth = Yii::$app->authManager;
        $auth->revokeAll($this->cachedId);
        parent::afterDelete();
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];   
    }

    public function attributeLabels()
    {
        return [
            'full_name' => Yii::t('app/models/user', 'Full Name'),
            'username' => Yii::t('app/models/user', 'Username'),
            'email' => Yii::t('app/models/user', 'Email'),
            'phone' => Yii::t('app/models/user', 'Phone'),
            'password' => Yii::t('app/models/user', 'Password'),
            'password_confirm' => Yii::t('app/models/user', 'Confirm Password'),
            'is_active' => Yii::t('app/models/user', 'Active'),
            'role' => Yii::t('app/models/user', 'Group'),
            'client_id' => Yii::t('app/models/order', 'Client'),
        ];
    }

    public function getSettings()
    {
        return $this->hasOne(UserSettings::className(), ['user_id' => 'id']);
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function assignRole()
    {
        $auth = Yii::$app->authManager;
        $fetchedRole = $auth->getRole($this->role);
        $auth->revokeAll($this->id);
        $auth->assign($fetchedRole, $this->id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    public function search($params)
    {
        $query = self::find();
        $this->load($params);

		return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function getRole()
    {
        $userRole = Yii::$app->authManager->getAssignments($this->id);
        return end($userRole);
    }

    public function getClient()
    {
        return $this->hasOne(RelateClientToUser::className(), ['user_id' => 'id']);
    }

    public function createClient()
    {
        if ($this->client_id && !$this->client) {
            (new RelateClientToUser([
                'client_id' => $this->client_id,
                'user_id' => $this->id
            ]))->save();
        }
    }

    public static function getFileAccess($file, $accessType = 'view')
    {
        $accessType = FileType::getAccessActions()[$accessType];
        $entity = $file->order;
        $user = User::findOne(Yii::$app->user->id);
        return FileAccess::find()->where([
            "file_type_id" => $file->fileType->id, 
            "status_id" => $entity->status_id, 
            "user_role" => $user->getRole()->roleName,
            'action_id' => $accessType['id']
        ])->one();
    }


    public function getFileAccessRules()
    {
        $user = User::findOne(Yii::$app->user->id);
        return FileAccess::find()
            ->where([
                "user_role" => $user->getRole()->roleName,
            ])
            ->all();
    }

    public function getAvailableOrderStatuses()
    {
        $userRole = $this->getRole()->roleName;
        return NextStatusOrder::find()
            ->select('id')
            ->where(['user_role_name' => $userRole])
            ->indexBy('id')
            ->all();
    }
}
