<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

class NextStatusOrder extends ActiveRecord
{
    public function rules()
    {
        return [
            [['status_key', 'next_status_key', 'user_role_name'], 'safe'],
            [['status_key', 'next_status_key', 'user_role_name'], 'required'],
        ];
    }

    public function getNextStatus()
    {
        return $this->hasOne(Status::className(), ['key' => 'next_status_key']);
    }

    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['key' => 'status_key']);
    }

    public static function checkNextStatus($statusKey, $nextStatusKey, $roleName){
        if(empty($statusKey)) {
            return false;
        }
        return self::find()
            ->where([
                'status_key' => $statusKey,
                'next_status_key' => $nextStatusKey,
                'user_role_name' => $roleName
            ])
            ->exists();
    }

    public static function saveNextStatuses($statusKey, $nextStatuses){
        foreach($nextStatuses as $statusIdAndRoleKey => $value){
            list($nextStatusKey, $userRoleKey) = explode('.' , $statusIdAndRoleKey);
            $nextStatusOrder = self::find()
                ->where([
                    'status_key' => $statusKey, 
                    'next_status_key' => $nextStatusKey, 
                    'user_role_name' => $userRoleKey
                ])
                ->one();

            if (!$value && $nextStatusOrder) {
                $nextStatusOrder->delete();
            }

            if($value && !$nextStatusOrder){
                $nextStatusOrder = new self([
                    'status_key' => $statusKey,
                    'next_status_key' => $nextStatusKey,
                    'user_role_name' => $userRoleKey
                ]);
                $nextStatusOrder->save();
            }
        }
    }
}
