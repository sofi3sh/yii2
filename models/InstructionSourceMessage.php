<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use \app\common\traits\SourceMessage;

class InstructionSourceMessage extends ActiveRecord
{
    use SourceMessage;

    const CATEGORY_DEFAULT = 'instruction';

    public function rules()
    {
        return [
            [['category', 'message'], 'safe'],
            [['category', 'message'], 'required'],
        ];
    }

}
