<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use \app\models\RbacSourceMessage;
use app\common\validators\CyrillicValidator;
use app\common\validators\NonWordCharacters;
use app\common\validators\AttributeNotChanged;
use yii\data\ActiveDataProvider;
use app\common\traits\SourceMessage;
use yii\helpers\ArrayHelper;

class AuthItem extends ActiveRecord
{
    use SourceMessage;

    const SCENARIO_CREATE_ROLE = 'createRole';
    const SCENARIO_UPDATE_ROLE = 'updateRole';
    const SCENARIO_CREATE_PERMISSION = 'createPermission';

    const TYPE_ROLE = 1;
    const TYPE_PERMISSION = 2;

    const ROLE_ADMIN = 'admin';
    const ROLE_CUSTOMER = 'customer';
    const ROLE_DESIGN_DEPARTMENT = 'design_department';
    const ROLE_CALCULATION_DEPARTMENT = 'calculation_department';
    const ROLE_TECHNOLOGY_DEPARTMENT = 'technology_department';
    const ROLE_DESIGNER_DEPARTMENT = 'designer';
    const ROLE_PRICING_DEPARTMENT = 'pricing';
    const ROLE_TECHNOLOGIST_DEPARTMENT = 'technologist';

    const PERMISSION_USERS_MANAGE = 'users/manage';
    const PERMISSION_USER_CREATE = 'user/create';
    const PERMISSION_AUTH_CREATE_ROLE = 'auth-item/create-role';
    const PERMISSION_AUTH_MANAGE_PERMISSIONS = 'auth-item-child/permissions';
    const PERMISSION_PRODUCT_MANAGE = 'product/manage';
    const PERMISSION_PRODUCT_OPTION_CREATE = 'product-option/create';
    const PERMISSION_PRODUCT_CREATE = 'product/create';
    const PERMISSION_VIEW_PRODUCT_LIST = 'product/index';
    const PERMISSION_FILES_SECTION = 'files_section';

    const ROLE_SCENARIOS = [
        self::SCENARIO_CREATE_ROLE,
        self::SCENARIO_UPDATE_ROLE
    ];

    public $translations;
    public $authItemAccess;
    public $relations = [
        'rbac_source_message_id' => 'titleSourceMessage'
    ];

    public function rules()
    {
        return [
            [['name', 'description', 'rbac_source_message_id', 'translations'], 'safe'],
            ['name', 'unique'],
            ['name', NonWordCharacters::className()],
            ['name', CyrillicValidator::className()],
            ['name', AttributeNotChanged::className(), 'on' => self::SCENARIO_UPDATE_ROLE],
            ['name', 'required', 'on' => [self::SCENARIO_CREATE_ROLE, self::SCENARIO_CREATE_PERMISSION]],
        ];
    }

    public function beforeSave($insert)
    {
        $this->menageFieldTranslations(
            'rbac_source_message_id', 
            $this->translations['rbac_source_message_id'],
            'rbac',
            '\app\models\RbacSourceMessage',
            '\app\models\RbacMessage'
        );

        if (parent::beforeSave($insert)) {
            if (in_array($this->scenario, self::ROLE_SCENARIOS)) {
                $this->type = self::TYPE_ROLE;
            } else {
                $this->type = self::TYPE_PERMISSION;
            }
            return true;
        }
        return false;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('UNIX_TIMESTAMP()'),
            ],
        ];   
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app/models/authItem', 'Key'),
            'description' => Yii::t('app/models/authItem', 'Name'),
        ];
    }

    public static function getAllRoles($fields = ['*'])
    {
        return self::find()
            ->select($fields)
            ->where([
                'type' => self::TYPE_ROLE
            ])
            ->all();
    }

    public static function getAllPermissions($fields = ['*'])
    {
        return self::find()->select($fields)->where([
            'type' => self::TYPE_PERMISSION
        ])->all();
    }

    public function getTitleSourceMessage()
    {
        return $this->hasOne(RbacSourceMessage::className(), ['id' => 'rbac_source_message_id']);
    }

    public function getTitle()
    {
        return isset($this->titleSourceMessage) ?  Yii::t('rbac', $this->titleSourceMessage->message) : $this->name;
    }

    public function getVisibleOrderStatuses() 
    {
        return $this->hasMany(RoleStatus::className(), ['role_name' => 'name']);
    }

    public function getVisibleOrderStatusesIds() 
    {
        return ArrayHelper::getColumn($this->visibleOrderStatuses, 'status_id');
    }

    public function search($params, $type = null)
    {
        $query = self::find();
        $this->load($params);
        $query->andFilterWhere(['type' => $type]);

		return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public static function addPermission($permissionName, $descriptions, $scenario = self::SCENARIO_CREATE_PERMISSION)
    {
        $permission = new self(['name' => $permissionName]);
        $permission->scenario = $scenario;
        if ($descriptions['en-US']) {
            $rbacSourceMessage = new RbacSourceMessage([
                'category' => RbacSourceMessage::CATEGORY_DEFAULT,
                'message' => $descriptions['en-US']
            ]);
            $rbacSourceMessage->save();
            $rbacSourceMessage->addTranslations($descriptions, '\app\models\RbacMessage');
        }

        $permission->rbac_source_message_id = $rbacSourceMessage->id;
        return $permission->save();
    }

    public function removeRole()
    {
        $auth = Yii::$app->authManager;
        $fetchedRole = $auth->getRole($this->name);
        return $auth->remove($fetchedRole);
    }
}
