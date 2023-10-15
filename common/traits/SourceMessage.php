<?php
namespace app\common\traits;

use Yii;
use yii\helpers\ArrayHelper;

trait SourceMessage {
    public function getDbSortingCondition($order) {
        return Yii::$app->language === 'en-US' ?
            ['message' => $order] : ['translation' => $order];
    }

    public function getTranslationsQuery($sourceMessagesTable) {
        return $query = self::find()->joinWith(['titleSourceMessage', $sourceMessagesTable]);
    }

    public function addTranslations($translations, $messageClass)
    {
        if (!$this->id) {
            throw new \Exception("Cannot add translations. The id field of $messageClass is empty");
        }

        foreach ($translations as $languageCode => $translation) {
            if ($languageCode == \Yii::$app->sourceLanguage) {
                continue;
            }
            $message = new $messageClass([
                'id' => $this->id,
                'language' => $languageCode,
                'translation' => $translation
            ]);
            $message->save();
        }
        return true;
    }

    public function updateSourceTranslations($translations, $messageClass)
    {
        if (!$this->id) {
            throw new \Exception("Cannot update translations. The id field of $messageClass is empty");
        }
        $existedTranslations = $this->translations;
        foreach ($existedTranslations as $translationToUpdate) {
            if (!isset($translations[$translationToUpdate->language])) {
                continue;
            }
            $translationToUpdate->translation = $translations[$translationToUpdate->language];
            $translationToUpdate->save();
        }
        return true;
    }

    public function menageFieldTranslations($fieldName, $translations, $translationCategory, $sourceMessageClass, $messageClass)
    {
        if (!$translations) {
            return false;
        }
        $callMethodOnTranslations = isset($this->$fieldName) ? 'updateFieldTranslations' : 'createFieldTranslations';
        $this->$callMethodOnTranslations($fieldName, $translations, $translationCategory, $sourceMessageClass, $messageClass);

        return true;
    }

    public function createFieldTranslations($fieldName, $translations, $translationCategory, $sourceMessageClass, $messageClass)
    {
        if (!$translations[Yii::$app->sourceLanguage]) {
            return false;
        }
        $sourceMessage = new $sourceMessageClass([
            'category' => $translationCategory,
            'message' => $translations[Yii::$app->sourceLanguage]
        ]);
        $sourceMessage->save();
        $sourceMessage->addTranslations($translations, $messageClass);
        $this->$fieldName = $sourceMessage->id;
        return true;
    }

    public function updateFieldTranslations($fieldName, $translations, $translationCategory, $sourceMessageClass, $messageClass)
    {
        if (empty($translations) || !isset($this->relations[$fieldName])) {
            return false;
        }
        $sourceMessageRelation = $this->relations[$fieldName];
        $sourceMessage = $this->$sourceMessageRelation;
        if ($translations[Yii::$app->sourceLanguage]) {
            $sourceMessage->message = $translations[Yii::$app->sourceLanguage];
            $sourceMessage->save();
        }
        $sourceMessage->updateSourceTranslations($translations, $messageClass);
        return true;
    }

    public function getFieldTranslations($field)
    {
        if (!$this->$field) {
            return false;
        }
        $fieldTranslations = $this->$field->translations;
        $result = ArrayHelper::index($fieldTranslations, function ($translation) {
            return $translation->language;
        });
        $result[Yii::$app->sourceLanguage] = $this->$field;
        return $result;
    }
}
