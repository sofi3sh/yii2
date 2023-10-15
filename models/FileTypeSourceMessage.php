<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use \app\common\traits\SourceMessage;
use \app\models\FileTypeMessage;

class FileTypeSourceMessage extends ActiveRecord
{
    use SourceMessage;

    const CATEGORY_DEFAULT = 'fileType';

    public function rules()
    {
        return [
            [['category', 'message'], 'safe'],
            [['category', 'message'], 'required'],
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(FileTypeMessage::className(), ['id' => 'id']);
    }
}
