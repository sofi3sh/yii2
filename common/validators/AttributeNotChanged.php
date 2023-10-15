<?php

namespace app\common\validators;

use yii\validators\Validator;

class AttributeNotChanged extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if ($model->getOldAttribute($attribute) !== $model->{$attribute}) {
            $this->addError(
                $model, 
                $attribute, 
                \Yii::t('validation', 'Attribute {attribute} can not be changed'),
                ['attribute' => $attribute]            
            );
        }
    }
}
