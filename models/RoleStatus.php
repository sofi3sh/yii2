<?php

namespace app\models;

use \Yii;
use Yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

class RoleStatus extends ActiveRecord
{
    public function rules()
    {
        return [
            [['role_name', 'status_id'], 'safe'],
            [['role_name', 'status_id'], 'required'],
        ];
    }

    public static function getAvailableStatusesForUser() {
        $auth = Yii::$app->authManager;
        $userId = Yii::$app->user->getId();
        $userRole = array_keys($auth->getRolesByUser($userId))[0];

        return self::find()
            ->select('status_id')
            ->where(['role_name' => $userRole])
            ->asArray()
            ->all();
    }

    public static function saveAccesses($authItem, $statuses)
    {
        $visibleStatuses = $authItem->visibleOrderStatusesIds ?
            $authItem->visibleOrderStatusesIds : [];
    
        foreach ($statuses as $key => $isChecked) {
            $authItemAccess = in_array($key, $visibleStatuses);
            if (!$isChecked && $authItemAccess) {
                self::find()
                    ->where([
                        'status_id' => $key,
                        'role_name' => $authItem->name
                    ])
                    ->one()
                    ->delete();
            }

            if ($isChecked && !$authItemAccess) {
                $roleStatusItem = new self([
                    'status_id' => $key,
                    'role_name' => $authItem->name
                ]);
                $roleStatusItem->save();
            }
        }
    }

    public static function checkAccess($orderId) 
    {
        if (!$orderId) {
            return false;
        }
        $avalaibleStatusesIds = ArrayHelper::getColumn(
            RoleStatus::getAvailableStatusesForUser(),
            'status_id'
        ); 
        $orderStatusId = Order::find()->where(['id' => $orderId])->one()->status_id;

        return in_array($orderStatusId, $avalaibleStatusesIds);
    }
}
