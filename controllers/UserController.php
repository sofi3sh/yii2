<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use \app\controllers\BaseController;
use yii\web\Response;
use app\models\User;
use \app\models\AuthItem;
use \app\models\Client;

class UserController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $roles = AuthItem::getAllRoles(['name']);
        $allClients = Client::find()->select('id, full_name')->all();
        
        if ($request->isGet) {
            $newUser = new User;
            $clientId = $request->get('client_id');
            if ($clientId) {
                $client = Client::find()->where(['id' => $clientId])->one();
                $newUser->full_name = $client->full_name;
                $newUser->email = $client->email;
                $newUser->phone = $client->phone;
                $newUser->client_id = $client->id;
            }
            return $this->render('create', [
                'model' => $newUser,
                'roles' => $roles,
                'allClients' => $allClients
            ]);
        }

        $newUser = new User($request->post('User'));
        $newUser->scenario = User::SCENARIO_REGISTER;
        $newUser->save();

        if (!$newUser->hasErrors()) {
            Yii::$app->session->setFlash('success', Yii::t('app/models/user', 'New User was successfully created'));
            $newUser = new User;
        }

        return $this->render('create', [
            'model' => $newUser,
            'roles' => $roles,
            'allClients' => $allClients
        ]);
    }

    public function actionIndex()
    {
        $searchModel = new User();
      
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $roles = AuthItem::getAllRoles(['name']);
        $userModel = User::findOne($id);
        $allClients = Client::find()->select('id, full_name')->all();

        if ($request->isGet) {
            return $this->render('update', [
                'model' => $userModel,
                'roles' => $roles,
                'allClients' => $allClients
            ]);
        }

        $userModel->scenario = User::SCENARIO_UPDATE;
        $userModel->setAttributes($request->post('User'));
        $userModel->assignRole();
        $userModel->save();

        if (!$userModel->hasErrors()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/user', 'The user was successfully updated')
            );
        }

        return $this->render('update', [
            'model' => $userModel,
            'roles' => $roles,
            'allClients' => $allClients
        ]);
    }

    public function actionDelete($id)
    {
        $userModel = User::findOne($id);

        if ($userModel->delete()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/user', 'The user was successfully deleted')
            );
        } else {
            Yii::$app->session->setFlash(
                'error', 
                Yii::t('app', 'Something went wrong')
            );
        }

        return $this->redirect('/user/index');
    }
}
