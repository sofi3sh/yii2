<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use \app\common\traits\SourceMessage;
use \app\models\PrintedFormMessage;

class PrintedFormSourceMessage extends ActiveRecord
{
    use SourceMessage;

    const CATEGORY_DEFAULT = 'printedForm';

    public function rules()
    {
        return [
            [['category', 'message'], 'safe'],
            [['category', 'message'], 'required'],
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(PrintedFormMessage::className(), ['id' => 'id']);
    }
}
