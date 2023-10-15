<?php

namespace app\common\validators;

use yii\validators\Validator;

class NonWordCharacters extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if (preg_match('/[\s#+%$]/', $model->$attribute)) {
            $this->addError(
                $model, 
                $attribute, 
                \Yii::t('validation', 'Attribute {attribute} can not contain non-word characters'), 
                ['attribute' => $attribute]
            );
        }
    }
}
