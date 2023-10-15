<?php

namespace app\controllers;

use Yii;
use \app\controllers\BaseController;
use app\models\Status;
use app\models\AuthItem;
use app\models\NextStatusOrder;

class StatusController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new Status();
      
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionSave()
    {
        $request = Yii::$app->request;
        $status = new Status;
        $statuses = Status::find()->all();
        $userRoles = AuthItem::getAllRoles(['name', 'rbac_source_message_id']);

        $updateStatusId = $request->get('id');
        
        if ($updateStatusId) {
            $status = Status::findOne($updateStatusId);
        }
        
        $status->setAttributes($request->post('Status'));

        if ($request->isGet || !$status->save()) {
            return $this->render('statusForm', [
                'model' => $status,
                'statuses' => $statuses,
                'userRoles' => $userRoles,
                'pageTitle' => $updateStatusId ? 'Edit the status' : 'Create a new status'
            ]);
        }
        NextStatusOrder::saveNextStatuses($status->key, $request->post('Status')['next_statuses']);
        Yii::$app->session->setFlash(
            'success', 
            Yii::t('app/models/status', 'New status was successfully created')
        );
        return $this->redirect('/status/index');
    }

    public function actionDelete($id)
    {
        $status = Status::findOne($id);
        if (!$status) {
            Yii::$app->session->setFlash(
                'error', 
                Yii::t('app', 'Something went wrong')
            );
            return $this->redirect('/status/index');
        }

        $status->delete();

        Yii::$app->session->setFlash(
            'success', 
            Yii::t('app/models/status', 'The status was successfully deleted')
        );
        return $this->redirect('/status/index');
    }
}
