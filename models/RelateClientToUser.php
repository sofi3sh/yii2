<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;

class RelateClientToUser extends ActiveRecord
{
    public function rules()
    {
        return [
            [['client_id', 'user_id'], 'safe'],
            [['client_id', 'user_id'], 'required'],
        ];
    }

}
