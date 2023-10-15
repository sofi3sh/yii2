<?php

namespace app\controllers;

use Yii;
use \app\controllers\BaseController;
use app\models\User;
use \yii\web\Cookie;

class MeasurementSystemController extends BaseController
{
    public function actionSwitch()
    {
        $request = Yii::$app->request;
        $measurementSystemId = $request->get('id');

        if (!$measurementSystemId || Yii::$app->user->isGuest) {
            return $this->redirect($request->referrer);
        }
        
        $user = User::findIdentity(Yii::$app->user->id);
        $userSettings = $user->settings;
        $userSettings->setAttributes(['measurement_system_id' => $measurementSystemId]);
        $userSettings->save();
        return $this->redirect($request->referrer);
    }
}
