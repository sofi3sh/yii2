<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use \app\common\traits\SourceMessage;
use \app\models\ProductMessage;

class ProductSourceMessage extends ActiveRecord
{
    use SourceMessage;

    const CATEGORY_DEFAULT = 'product';

    public function rules()
    {
        return [
            [['category', 'message'], 'safe'],
            [['category', 'message'], 'required'],
        ];
    }

    public function getTranslations()
    {
        return $this->hasMany(ProductMessage::className(), ['id' => 'id']);
    }
}
