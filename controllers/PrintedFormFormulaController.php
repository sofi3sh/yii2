<?php

namespace app\controllers;

use Yii;
use \app\controllers\BaseController;
use \app\models\PrintedFormFormula;

class PrintedFormFormulaController extends BaseController
{
    public function actionIndex()
    {
        $queryParams = Yii::$app->request->get('PrintedFormFormula');
        $searchModel = new PrintedFormFormula();
        
        $searchModel->setAttributes($queryParams);
      
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $searchModel
        ]);
    }

    public function actionCreate()
    {
        $newFormula = new PrintedFormFormula();
        $formulas = PrintedFormFormula::find()
            ->orderBy(['is_system' => SORT_ASC])
            ->all();
        $request = Yii::$app->request;

        if ($request->isGet) {
            return $this->render('create', [
                'model' => $newFormula,
                'formulas' => $formulas
            ]);
        }

        $newFormula = new PrintedFormFormula($request->post('PrintedFormFormula'));
        $newFormula->save();

        if (!$newFormula->hasErrors()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/printedFormFormula', 'The formula was successfully created')
            );
            return $this->redirect('/printed-form-formula/index');
        }

        return $this->render('create', [
            'model' => $newFormula,
            'formulas' => $formulas
        ]);
    }


    public function actionUpdate($id)
    {
        $newFormula = PrintedFormFormula::findOne($id);
        $formulas = PrintedFormFormula::find()
            ->orderBy(['is_system' => SORT_ASC])
            ->all();
        $request = Yii::$app->request;

        if ($request->isGet) {
            return $this->render('update', [
                'model' => $newFormula,
                'formulas' => $formulas
            ]);
        }

        $newFormula->setAttributes($request->post('PrintedFormFormula'));
        $newFormula->save();

        if (!$newFormula->hasErrors()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/printedFormFormula', 'The formula was successfully updated')
            );
            return $this->redirect('/printed-form-formula/index');
        }

        return $this->render('update', [
            'model' => $newFormula,
            'formulas' => $formulas
        ]);
    }

    public function actionDelete($id)
    {
        $printedForm = PrintedFormFormula::findOne($id);

        if ($printedForm->delete()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/printedFormFormula', 'The formula was successfully deleted')
            );
        }

        return $this->redirect('/printed-form-formula/index');
    }
}
