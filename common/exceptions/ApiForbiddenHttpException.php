<?php

namespace app\common\exceptions;

use Yii;
use yii\web\ForbiddenHttpException;

class ApiForbiddenHttpException extends ForbiddenHttpException 
{
    public function __construct() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $errorMessage = Yii::t('app', 'You do not have access for performing this action');
        parent::__construct($errorMessage, 403);
    }
}