<?php

namespace app\controllers;

use Yii;
use \app\controllers\BaseController;
use app\models\User;
use app\models\Client;

class ClientController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new Client();
      
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $users = User::find()->select('id, full_name')->all();

        if ($request->isGet) {
            return $this->render('create', [
                'model' => new Client,
                'users' => $users
            ]);
        }

        $newClient = new Client($request->post('Client'));
        $newClient->save();

        if (!$newClient->hasErrors()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/client', 'The client was successfully created')
            );
            return $this->redirect('/client/index');
        }

        return $this->render('create', [
            'model' => $newClient,
            'users' => $users
        ]);
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $users = User::find()->select('id, full_name')->all();
        $client = Client::find()->where(['id' => $id])->one();

        if ($request->isGet) {
            return $this->render('update', [
                'model' => $client,
                'users' => $users
            ]);
        }
        
        $client->setAttributes($request->post('Client'));
        $client->save();

        if (!$client->hasErrors()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/client', 'The client was successfully updated')
            );
        }

        return $this->redirect('/client/index');
    }
}
