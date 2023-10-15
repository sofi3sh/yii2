<?php
namespace app\common\helpers\views;

use yii\helpers\Html;
use app\models\Language;

class TranslationHelper extends HtmlHelper
{
    public static function renderFieldWithTranslations($modelClass, $fieldName, $label, $model = null)
    {
       $result = '';
       $translations = $model ? $model->getFieldTranslations('titleSourceMessage') : [];
       foreach(Language::LANGUAGES_LIST as $languageSettings) {
            $currenTranslation = !empty($translations) && isset($translations[$languageSettings['code']]) ? $translations[$languageSettings['code']] : null;
            $translationMessage = isset($currenTranslation['message']) ? $currenTranslation['message'] : $currenTranslation['translation'];
            $result .= '<div class="row form-group">
                    <label class="col-lg-2 col-form-label">' .
                        $languageSettings['title'] . ' - ' . $label . 
                    '</label>
                    <div class="col-lg-10">' .
                        Html::input(
                            'text', 
                            "{$modelClass}[translations][$fieldName][{$languageSettings['code']}]", 
                            $currenTranslation ? $translationMessage : null, 
                            ['class' => 'form-control']
                        ) .
                    '</div>
                </div>';
        }

        return $result;
    }
}
