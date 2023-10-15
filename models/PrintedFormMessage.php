<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;

class PrintedFormMessage extends ActiveRecord
{
    public function rules()
    {
        return [
            [['language', 'translation'], 'safe'],
            [['language', 'translation'], 'required'],
        ];
    }

}
