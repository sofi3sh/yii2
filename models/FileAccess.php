<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;

class FileAccess extends ActiveRecord
{
    public function rules()
    {
        return [
            [['file_type_id', 'status_id', 'user_role', 'action_id'], 'safe'],
            [['file_type_id', 'status_id', 'user_role', 'action_id'], 'required'],
        ];
    }

    public static function checkAccesses($fileTypeId, $statusId, $userRole, $actionId)
    {
        if (empty($fileTypeId)) {
            return false;
        }
        return self::find()
        ->where([
            'file_type_id' => $fileTypeId,
            'status_id' => $statusId,
            'user_role' => $userRole,
            'action_id' => $actionId
        ])
        ->exists();
    }

    public static function saveAccesses($fileTypeId, $statuses){
        foreach($statuses as $key => $isChecked){
            list($statusId, $userRoleKey, $actionId) = explode('.' , $key);
            $fileAccess = self::find()
                ->where([
                    'file_type_id' => $fileTypeId,
                    'status_id' => $statusId,
                    'user_role'=>$userRoleKey,
                    'action_id'=>$actionId
                ])
                ->one();

            if (!$isChecked && $fileAccess) {
                $fileAccess->delete();
            }

            if($isChecked && !$fileAccess){
                $fileAccess = new self([
                    'file_type_id' => $fileTypeId,
                    'status_id' => $statusId,
                    'user_role'=>$userRoleKey,
                    'action_id'=>$actionId
                ]);
                $fileAccess->save();
            }
        }
    }
}
