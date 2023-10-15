<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class AuthItemChild extends ActiveRecord
{
    public function rules()
    {
        return [
            [['parent', 'child'], 'safe'],
        ];
    }

    public static function assignPermissionsToRole($role, $permissions)
    {
        $authManager = Yii::$app->authManager;
        foreach ($permissions as $permissionName) {
            $authPermission = $authManager->getPermission($permissionName);
            $existingPermissions = $authManager->getPermissionsByRole($role);

            if (!array_key_exists($permissionName, $existingPermissions)) {
                $authRole = $authManager->getRole($role);
                $authManager->addChild($authRole, $authPermission);
            }
        }
    }
}
