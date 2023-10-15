<?php

namespace app\common\validators;

use yii\validators\Validator;

class CyrillicValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if (self::isCyrillicCharacters($model->$attribute)) {
            $this->addError(
                $model, 
                $attribute, 
                \Yii::t('validation', 'Attribute {attribute} can not contain Cyrillic characters'), 
                ['attribute' => $attribute]
            );
        }
    }

    public static function isCyrillicCharacters($text)
    {
        return preg_match('/\p{Cyrillic}+/u', $text);
    }
}
