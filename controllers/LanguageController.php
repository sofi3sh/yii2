<?php

namespace app\controllers;

use Yii;
use \app\controllers\BaseController;
use app\models\User;
use \yii\web\Cookie;

class LanguageController extends BaseController
{
    public function actionSwitch()
    {
        $request = Yii::$app->request;
        if (!$languageId = $request->get('id')) {
            return $this->redirect($request->referrer);
        }

        if (Yii::$app->user->isGuest) {
            $cookies = Yii::$app->response->cookies;
            $cookies->add(new Cookie([
                'name' => 'language_id',
                'value' => $languageId,
            ]));
            return $this->redirect($request->referrer);
        }
        
        $user = User::findIdentity(Yii::$app->user->id);
        $userSettings = $user->settings;
        $userSettings->setAttributes(['language_id' => $languageId]);
        $userSettings->save();
        return $this->redirect($request->referrer);
    }
}
