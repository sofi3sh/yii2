<?php

namespace app\controllers;

use Yii;
use \app\controllers\BaseController;
use \app\models\ProductOption;
use \app\models\Product;
use \app\models\ProductSourceMessage;

class ProductOptionController extends BaseController
{
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $productOptions = ProductOption::find()->select('id, title_source_message_id')->all();
        $products = Product::find()->select('id, title_source_message_id')->all();
        $availablePreviousOptions = ProductOption::find()
            ->select('id, title_source_message_id')
            ->where([
                'is_dynamic' => ProductOption::IS_DYNAMIC,
            ])
            ->andWhere(['not',['option_type' => ProductOption::DROPDOWN_CHILD_OPTION_KEY]])
            ->all();

        if ($request->isGet) {
            return $this->render('create', [
                'model' => new ProductOption(['scenario' => ProductOption::SCENARIO_CREATE]),
                'productOptions' => $productOptions,
                'availablePreviousOptions' => $availablePreviousOptions,
                'products' => $products
            ]);
        }
        
        $newProduct = new ProductOption($request->post()['ProductOption']);
        $newProduct->save();

        if (!$newProduct->hasErrors()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/productOption', 'New product option was successfully created')
            );
            $newProduct = new ProductOption;
        }

        return $this->render('create', [
            'model' => $newProduct,
            'productOptions' => $productOptions,
            'products' => $products,
            'availablePreviousOptions' => $availablePreviousOptions
        ]);
    }

    public function actionIndex()
    {
        $searchModel = new ProductOption();
      
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $productOption = ProductOption::findOne($id);
        $productOption->scenario = ProductOption::SCENARIO_UPDATE;
        $productOptions = ProductOption::find()->select('id, title_source_message_id')->all();
        $products = Product::find()->select('id, title_source_message_id')->all();
        $availablePreviousOptions = ProductOption::find()
            ->select('id, title_source_message_id')
            ->where([
                'is_dynamic' => ProductOption::IS_DYNAMIC,
                'product_id' => $productOption->product_id
            ])
            ->andWhere(['not',['option_type' => ProductOption::DROPDOWN_CHILD_OPTION_KEY]])
            ->andWhere(['not',['id' => $id]])
            ->all();

        if ($request->isGet) {
            return $this->render('update', [
                'model' => $productOption,
                'productOptions' => $productOptions,
                'products' => $products,
                'availablePreviousOptions' => $availablePreviousOptions
            ]);
        }
        
        $productOption->setAttributes($request->post('ProductOption'));
        $productOption->save();

        if (!$productOption->hasErrors()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/productOption', 'The product option was successfully updated')
            );
        }

        return $this->render('update', [
            'model' => $productOption,
            'productOptions' => $productOptions,
            'availablePreviousOptions' => $availablePreviousOptions,
            'products' => $products
        ]);
    }

    public function actionDelete($id)
    {
        $productModel = ProductOption::findOne($id);

        if ($productModel->delete()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/productOption', 'The option was successfully deleted')
            );
        } else {
            Yii::$app->session->setFlash(
                'error', 
                Yii::t('app', 'Something went wrong')
            );
        }

        return $this->redirect('/product-option/index');
    }
}
