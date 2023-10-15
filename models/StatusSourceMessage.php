<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use \app\common\traits\SourceMessage;
use app\models\StatusMessage;

class StatusSourceMessage extends ActiveRecord
{
    use SourceMessage;
    
    public function rules()
    {
        return [
            [['category', 'message'], 'safe'],
            [['category', 'message'], 'required'],
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(StatusMessage::className(), ['id' => 'id']);
    }
}
