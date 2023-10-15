<?php

namespace app\controllers;

use Yii;
use \app\controllers\BaseController;
use \app\models\Order;
use \app\models\StatusLog;
use \app\models\RoleStatus;
use yii\web\ForbiddenHttpException;

class OrderController extends BaseController
{
    public function actionCreate()
    {
        return $this->render('create');
    }

    public function actionUpdate($id)
    {
        $hasAccess = RoleStatus::checkAccess($id);
        if (!$hasAccess) {
            throw new ForbiddenHttpException(
                Yii::t('app', 'You do not have access for performing this action')
            );
        }
        
        return $this->render('update');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView($id)
    {
        $order = Order::findOne($id);
        return $this->render('view', [
            'order' => $order
        ]);
    }

    public function actionComments($id)
    {
        $order = Order::findOne($id);
        $statusLogs = StatusLog::find()
            ->where([
                'order_id' => $id
            ])
            ->andWhere(['IS NOT', 'comment', null])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
        return $this->render('comments', [
            'statusLogs' => $statusLogs,
            'order' => $order
        ]);
    }
}
