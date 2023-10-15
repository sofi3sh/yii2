<?php

namespace app\models;

use \Yii;

class MeasurementSystem
{
    const METRIC = 1;
    const IMPERIAL = 2;

    const MEASUREMENT_SYSTEMS_LIST = [
        self::METRIC => [
            'id' => self::METRIC,
            'title' => 'Metric',
            'defaultLengthUnit' => 'mm',
        ],
        self::IMPERIAL => [
            'id' => self::IMPERIAL,
            'title' => 'Imperial',
            'defaultLengthUnit' => 'inch'
        ]
    ];

    public static function convertLength($value, $convertTo = null)
    {
        if (!$value) {
            return 0;
        }
        
        $user = User::findIdentity(Yii::$app->user->id);
        $userMeasurementSystemId = $user->settings->measurement_system_id;
        if ($userMeasurementSystemId == Yii::$app->params['defaultMeasurementSystemId']) {
            return $value;
        }
        if (!$convertTo) {
            $convertTo = self::MEASUREMENT_SYSTEMS_LIST[$userMeasurementSystemId]['defaultLengthUnit'];
        }
        switch ($convertTo) {
            case 'mm':
                return $value * 25.4;
            
            case 'inch':
                return $value * 0.0393700787;
            default:
                throw new \Exception("The value cannot be converted to the $convertTo measurement unit");
        }
    }
}
