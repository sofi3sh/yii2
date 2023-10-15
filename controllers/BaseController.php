<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\Language;
use app\models\FileType;

class BaseController extends Controller
{
    public $layout = 'main';

    public function beforeAction($action)
    {
        Yii::$app->params['dataFolder'] = Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web/uploads';
        Yii::$app->params['fileFolders'] = [
            'files' => Yii::$app->params['dataFolder'] . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR,
            'printed_templates' => Yii::$app->params['dataFolder'] . DIRECTORY_SEPARATOR . 'printed_formula_images' . DIRECTORY_SEPARATOR
        ];
        
        if (!file_exists(Yii::$app->params['fileFolders']['files'])) {
            mkdir(Yii::$app->params['fileFolders']['files'], 0777);
        }

        if (!parent::beforeAction($action)) {
            return false;
        }

        if (Yii::$app->user->isGuest && Yii::$app->controller->action->id != 'login') {
            return $this->redirect('/site/login')->send();
        }

        if (Yii::$app->user->isGuest) {
            $cookies = Yii::$app->request->cookies;
            $languageId = $cookies->getValue('language_id', Language::EN_ID);;
            Yii::$app->language = Language::LANGUAGES_LIST[$languageId]['code'];
        } else {
            $user = User::findIdentity(Yii::$app->user->id);
            Yii::$app->language = $user->settings->getLanguage()['code'];
        }

        return true;
    }
}
