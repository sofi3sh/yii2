<?php
namespace app\common\helpers\views;

use Yii;

class ModelAttributeHelper {
    public static function getOldAttributeValueOnUpdateAction($model, $attribute) {
        
        $action = Yii::$app->controller->action->id;

        if ($action === 'update') {
            return $model->getOldAttribute($attribute);
        }

        return $model->{$attribute};

    }
}