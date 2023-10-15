<?php

namespace app\controllers;

use Yii;
use \app\controllers\BaseController;
use \app\models\PrintedFormGroup;
use \app\models\PrintedFormTemplate;

class PrintedFormGroupController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new PrintedFormGroup();
      
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionSave($id = null)
    {
        $printedFormGroup = new PrintedFormGroup();
        $templates = PrintedFormTemplate::find()->all();
        $request = Yii::$app->request;
        $pageTitle = Yii::t('app/models/printedFormGroup', 'Create a new group template');

        if ($id) {
            $printedFormGroup = PrintedFormGroup::findOne($id);
            $pageTitle = Yii::t('app/models/printedFormGroup', 'Update group template');
        }

        if ($request->isGet) {
            return $this->render('save', [
                'model' => $printedFormGroup,
                'templates' =>$templates,
                'title' => $pageTitle
            ]);
        }

        $printedFormGroup->setAttributes($request->post('PrintedFormGroup'));
        $printedFormGroup->save();

        if (!$printedFormGroup->hasErrors()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app', 'All changes were saved')
            );
            return $this->redirect('/printed-form-group/index');
        }

        return $this->render('save', [
            'model' => $printedFormGroup,
            'templates' => $templates,
            'title' => $pageTitle
        ]);
    }

    public function actionDelete($id)
    {
        $printedFormGroup = PrintedFormGroup::findOne($id);

        if ($printedFormGroup->delete()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/printedFormGroup', 'The group was successfully deleted')
            );
        }

        return $this->redirect('/printed-form-group/index');
    }
}
