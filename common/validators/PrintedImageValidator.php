<?php

namespace app\common\validators;

use yii\validators\Validator;
use app\models\FileType;

class PrintedImageValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if (!in_array(strtolower($model->extension), FileType::IMAGE_EXTENSIONS) 
            && $model->isPrintedTemplateImage()) {
            $this->addError(
                $model, 
                $attribute, 
                \Yii::t(
                    'validation', 
                    'Printed template image type can be assigned only to files with extensions of {allowed_extensions}'
                ),
                ['allowed_extensions' => implode(', ', FileType::IMAGES_EXTENSIONS)]
            );
        }
    }
}
