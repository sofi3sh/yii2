<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use \app\models\Language;

class UserSettings extends ActiveRecord
{
    public function rules()
    {
        return [
            [['user_id', 'language_id', 'measurement_system_id'], 'integer'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];   
    }

    public function getLanguage()
    {
        return Language::LANGUAGES_LIST[$this->language_id];
    }

    public function getMeasurementSystem()
    {
        return MeasurementSystem::MEASUREMENT_SYSTEMS_LIST[$this->measurement_system_id];
    }
}
