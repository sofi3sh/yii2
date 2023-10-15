<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use \app\common\traits\SourceMessage;
use \app\models\RbacMessage;

class RbacSourceMessage extends ActiveRecord
{
    use SourceMessage;

    const CATEGORY_DEFAULT = 'rbac';

    public function rules()
    {
        return [
            [['category', 'message'], 'safe'],
            [['category', 'message'], 'required'],
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(RbacMessage::className(), ['id' => 'id']);
    }
}
