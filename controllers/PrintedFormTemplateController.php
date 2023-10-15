<?php

namespace app\controllers;

use Yii;
use \app\controllers\BaseController;
use \app\models\PrintedFormTemplate;
use \app\models\PrintedFormFormula;
use \app\models\OrderProductOption;
use \app\models\Order;

class PrintedFormTemplateController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new PrintedFormTemplate();
      
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $newTemplate = new PrintedFormTemplate();
        $formulas = PrintedFormFormula::find()
            ->orderBy(['is_system' => SORT_ASC])
            ->all();
        $request = Yii::$app->request;

        if ($request->isGet) {
            return $this->render('create', [
                'model' => $newTemplate,
                'formulas' => $formulas
            ]);
        }

        $newTemplate = new PrintedFormTemplate($request->post('PrintedFormTemplate'));
        $newTemplate->save();

        if (!$newTemplate->hasErrors()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/printedFormTemplate', 'The template was successfully created')
            );
            return $this->redirect('/printed-form-template/index');
        }

        return $this->render('create', [
            'model' => $newTemplate,
            'formulas' => $formulas
        ]);
    }

    public function actionUpdate($id)
    {
        $template = PrintedFormTemplate::findOne($id);
        $formulas = PrintedFormFormula::find()
            ->orderBy(['is_system' => SORT_ASC])
            ->all();
        $request = Yii::$app->request;

        if ($request->isGet) {
            return $this->render('update', [
                'model' => $template,
                'formulas' => $formulas
            ]);
        }

        $template->setAttributes($request->post('PrintedFormTemplate'));
        $template->save();

        if (!$template->hasErrors()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/printedFormTemplate', 'The template was successfully updated')
            );
            return $this->redirect('/printed-form-template/index');
        }

        return $this->render('update', [
            'model' => $template,
            'formulas' => $formulas
        ]);
    }

    public function actionDelete($id)
    {
        $printedForm = PrintedFormTemplate::findOne($id);

        if ($printedForm->delete()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/printedFormTemplate', 'The template was successfully deleted')
            );
        }

        return $this->redirect('/printed-form-template/index');
    }

    public function actionPrint()
    {
        $this->layout = 'print';
        $request = Yii::$app->request->get();
        $order = Order::findOne($request['order']);
        $template = PrintedFormTemplate::findOne($request['template']);

        $templateData = [
            'order' => $order,
            'user' => $order->user,
            'client' => $order->client,
            'product' => $order->product,
            'option' => OrderProductOption::mapProductOptionsWithValues($request['order']),
            'modules' => $order->modules
        ];
        preg_match_all('/{.*?}/i', $template->template, $templateMarks);
        $templateMarks = array_unique($templateMarks[0]);

        if(!empty($templateMarks)){
            $template->template = $template->renderTemplate($template->template, $templateMarks, $templateData);
        }
        
        return $this->render('print', [
            'order' => $order,
            'template' => $template
        ]);
    }
}
