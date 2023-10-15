<?php

namespace app\controllers\api\v1;

use Yii;
use \app\models\Status;
use \app\models\User;
use \app\models\Order;
use \app\models\NextStatusOrder;
use app\controllers\api\v1\BaseController;
use app\models\RoleStatus;
use app\common\exceptions\ApiForbiddenHttpException;

class OrderStatusController extends BaseController
{
    public $modelClass = 'app\models\Status';

    public function actionNext()
    {
        $requestData = json_decode(Yii::$app->request->getRawBody(), true);
        $orderId = $requestData['orderId'];
        $order = Order::find()
            ->where(['id' => $orderId])
            ->one();
        $statusChanged = $order->switchToNextStatus($requestData);

        if (!$statusChanged) {
            throw new ApiForbiddenHttpException();
        }

        $newStatus = Order::getCurrentStatus($order->id);
        Yii::$app->response->statusCode = 200;

        return $this->asJson([
            'success' => true,
            'newStatus' => $newStatus,
            'errors' => []
        ]);
    }

    public function actionPrevious()
    {
        $requestData = json_decode(Yii::$app->request->getRawBody(), true);
        $orderId = $requestData['orderId'];
        $order = Order::find()
            ->where(['id' => $orderId])
            ->one();
        $statusChanged = $order->switchToPreviousStatus($requestData);

        if (!$statusChanged) {
            throw new ApiForbiddenHttpException();
        }

        $newStatus = Order::getCurrentStatus($order->id);
        Yii::$app->response->statusCode = 200;

        return $this->asJson([
            'success' => true,
            'newStatus' => $newStatus,
            'errors' => []
        ]);
    }
    
    public function actionStatuses()
    {
        $user = User::findOne(Yii::$app->user->getId());
        $userRole = $user->getRole()->roleName;
        $nextStatuses = Status::find()
            ->with([
                'nextStatuses' => function ($query) use ($userRole) {
                    $query->where(['user_role_name' => $userRole]);
                    $query->indexBy('next_status_key');
                    $query->with([
                        'nextStatus' => function ($query) {
                            $query->with([
                                'titleSourceMessage' => function ($query) {
                                    $query->with([
                                        'translations' => function ($query) {
                                            $query->where(['language' => Yii::$app->language]);
                                        }
                                    ]);
                                }
                            ]);
                        }
                    ]);
                },
                'titleSourceMessage' => function ($query) {
                    $query->with([
                        'translations' => function ($query) {
                            $query->where(['language' => Yii::$app->language]);
                        }
                    ]);
                },
                'commentReasons' => function ($query) use ($userRole) {
                    $query->with([
                        'titleSourceMessage' => function ($query) {
                            $query->with([
                                'translations' => function ($query) {
                                    $query->where(['language' => Yii::$app->language]);
                                }
                            ]);
                        }
                    ]);
                },
            ])
            ->indexBy('key')
            ->asArray()
            ->all();
            
        return $this->asJson([
            'statuses' => $nextStatuses,
        ]);
    }
}
