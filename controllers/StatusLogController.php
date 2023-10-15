<?php

namespace app\controllers;

use Yii;
use \app\controllers\BaseController;
use app\models\User;
use app\models\StatusLog;

class StatusLogController extends BaseController
{
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $users = User::find()->select('id, full_name')->all();
        $searchModel = new StatusLog();
        if($request->post('StatusLog')) {
            $searchModel->attributes = $request->post('StatusLog');
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $searchModel,
            'users' => $users
        ]);
    }

}
