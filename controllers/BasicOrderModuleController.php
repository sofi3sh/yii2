<?php

namespace app\controllers;

use Yii;
use \app\controllers\BaseController;
use \app\models\BasicOrderModule;

class BasicOrderModuleController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new BasicOrderModule();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionUpload()
    {
        $requestData = Yii::$app->request->post();
        $basicModule = new BasicOrderModule();
        $modulesSaved = $basicModule->saveParsedModules();

        if ($modulesSaved) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/basicOrderModule', 'The file with modules was successfully uploaded')
            );
        }

        return $this->redirect('/basic-order-module/index/');
    }

    public function actionUpdate($id)
    {
        $basicModule = BasicOrderModule::findOne($id);
        $basicModule->scenario = BasicOrderModule::SCENARIO_UPDATE;
        $request = Yii::$app->request;

        if (!$basicModule) {
            return $this->redirect('/basic-order-module/index');
        }

        if ($request->isGet) {
            return $this->render('update', [
                'basicModule' => $basicModule
            ]);
        }

        $basicModule->setAttributes($request->post('BasicOrderModule'));
        $basicModule->save();

        if (!$basicModule->hasErrors()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/basicOrderModule', 'The typical fragment was successfully updated')
            );
            return $this->redirect('/basic-order-module/index');
        }

        return $this->render('update', [
            'basicModule' => $basicModule
        ]);
    }

    public function actionDelete($id)
    {
        $basicModule = BasicOrderModule::findOne($id);

        if ($basicModule->delete()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/basicOrderModule', 'The typical fragment was successfully deleted')
            );
        }

        return $this->redirect('/basic-order-module/index');
    }
}
