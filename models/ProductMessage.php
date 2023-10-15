<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;

class ProductMessage extends ActiveRecord
{
    public function rules()
    {
        return [
            [['language', 'translation'], 'safe'],
            [['language', 'translation'], 'required'],
        ];
    }

}
