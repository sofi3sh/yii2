<?php

namespace app\common\validators;

use yii\validators\Validator;
use \app\models\Language;

class TranslationValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $sourceLanguage = \Yii::$app->sourceLanguage;
        $sourceLanguageTitle =  Language::LANGUAGES_LIST[Language::EN_ID]['title'];
        if (!array_key_exists($attribute, $model->translations) || empty($model->translations[$attribute][$sourceLanguage])) {
            $this->addError(
                $model, 
                $attribute, 
                \Yii::t('validation', 'The "{attribute}" attribute must exist at least for {sourceLanguage} language'), 
                ['attribute' => $attribute, 'sourceLanguage' => $sourceLanguageTitle]
            );
        }
    }
}
