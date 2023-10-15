<?php

namespace app\controllers;

use Yii;
use \app\controllers\BaseController;
use \app\models\Product;
use \app\models\ProductOption;
use \app\models\ProductProductOption;

class ProductController extends BaseController
{
    public function actionCreate()
    {
        $request = Yii::$app->request;
        
        if ($request->isGet) {
            return $this->render('create', [
                'model' => new Product(['scenario' => Product::SCENARIO_CREATE])
            ]);
        }

        $newProduct = new Product($request->post('Product'));
        $newProduct->save();    

        if (!$newProduct->hasErrors()) {
            Yii::$app->session->setFlash('success', Yii::t('app/models/product', 'New product was successfully created'));
            $newProduct = new Product;
        }

        $availableProductOptions = ProductOption::find()
            ->andWhere(['not', ['option_type' => ProductOption::DROPDOWN_CHILD_OPTION_KEY]])
            ->all();

        return $this->render('create', [
            'model' => $newProduct,
            'availableProductOptions' => $availableProductOptions
        ]);
    }

    public function actionIndex()
    {
        $searchModel = new Product();
      
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $productModel = Product::findOne($id);
        $productModel->scenario = Product::SCENARIO_UPDATE;
        $availableProductOptions = ProductOption::find()
            ->andWhere(['not', ['option_type' => ProductOption::DROPDOWN_CHILD_OPTION_KEY]])
            ->all();

        if ($request->isGet) {
            return $this->render('update', [
                'model' => $productModel,
                'availableProductOptions' => $availableProductOptions
            ]);
        }

        $productModel->setAttributes([
            'translations' => $request->post('Product')['translations']
        ]);

        $productModel->setAttributes($request->post('Product'));

        $productModel->save();

        if (!$productModel->hasErrors()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/product', 'The product was successfully updated')
            );
        }

        return $this->render('update', [
            'model' => $productModel,
            'availableProductOptions' => $availableProductOptions
        ]);
    }

    public function actionView($id)
    {
        $productModel = Product::findOne($id);
        $availableProductOptions = ProductOption::find()
            ->andWhere(['not', ['option_type' => ProductOption::DROPDOWN_CHILD_OPTION_KEY]])
            ->all();

        return $this->render('view', [
            'model' => $productModel,
            'availableProductOptions' => $availableProductOptions
        ]);
    }

    public function actionDelete($id)
    {
        $productModel = Product::findOne($id);
        $productModel->delete();

        Yii::$app->session->setFlash(
            'success', 
            Yii::t('app/models/product', 'The product was successfully deleted')
        );

        return $this->redirect(Yii::$app->request->referrer);
    }
}
