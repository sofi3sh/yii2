<?php

namespace app\controllers\api\v1;

use Yii;
use yii\rest\ActiveController;
use app\models\User;

class BaseController extends ActiveController
{
    public function beforeAction($action)
    {
        Yii::$app->params['dataFolder'] = Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web/uploads/';
        Yii::$app->params['fileFolders'] = [
            'files' => Yii::$app->params['dataFolder'] . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR,
            'products' => Yii::$app->params['dataFolder'] . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR,
        ];
        
        if (!parent::beforeAction($action)) {
            return false;
        }

        $user = User::findIdentity(Yii::$app->user->id);
        Yii::$app->language = $user->settings->getLanguage()['code'];

        return true;
    }
}
