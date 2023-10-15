<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;

class InstructionMessage extends ActiveRecord
{
    public function rules()
    {
        return [
            [['language', 'translation'], 'safe'],
            [['language', 'translation'], 'required'],
        ];
    }
}
