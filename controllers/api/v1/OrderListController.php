<?php

namespace app\controllers\api\v1;

use Yii;
use \app\models\Order;
use \app\models\Product;
use \app\models\User;
use \app\models\Client;
use \app\models\PrintedFormTemplate;
use \app\models\RoleStatus;
use Yii\helpers\ArrayHelper;
use app\controllers\api\v1\BaseController;
use app\models\Status;

class OrderListController extends BaseController
{
    public $modelClass = 'app\models\Order';

    public function actionOrders() 
    {
        $searchModel = new Order();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $paginationData['basicInfo'] = $dataProvider->getPagination();
        $paginationData['currentPage'] = $dataProvider->pagination->getPage();

        $ordersWithReplacedStatuses = array_map(function($order) {
            $currentStatus = Order::getCurrentStatus($order['id']);
            $order['status_id'] = $currentStatus['id'];
            $order['status'] = $currentStatus;

            return $order;
        }, $dataProvider->getModels());
        
        return $this->asJson(['orders' => [
            'orders' => $ordersWithReplacedStatuses,
            'pagination' => $paginationData
        ]]);
    }

    public function actionTranslations()
    {
        $translations = [
            'ID' => Yii::t('app/models/order', 'ID'),
            'Order #' => Yii::t('app/models/order', 'Order #'),
            'User' => Yii::t('app/models/order', 'User'),
            'Client' => Yii::t('app/models/order', 'Client'),
            'Product' => Yii::t('app/models/order', 'Product'),
            'Actions' => Yii::t('app', 'Actions'),
            'Status' => Yii::t('app/models/order', 'Status'),
            'Current Status' => Yii::t('app/models/order', 'Current Status'),
            'Next Status' => Yii::t('app/models/order', 'Next Status'),
            'Close' => Yii::t('app', 'Close'),
            'Next' => Yii::t('app', 'Next'),
            'Previous' => Yii::t('app', 'Previous'),
            'Loading...' => Yii::t('app', 'Loading...'),
            'Edit' => Yii::t('app', 'Edit'),
            'View' => Yii::t('app', 'View'),
            'Delete' => Yii::t('app', 'Delete'),
            'Search' => Yii::t('app', 'Search'),
            'Select one' => Yii::t('app', 'Select one'),
            'Discard' => Yii::t('app', 'Discard'),
            "The order's status was updated" => Yii::t('app/models/order', "The order's status was updated"),
            'Yes' => Yii::t('app', 'Yes'),
            'Do you want to delete this order?' => Yii::t('app/models/order', 'Do you want to delete this order?'),
            'The order was deleted' => Yii::t('app/models/order', 'The order was deleted'),
            'Something went wrong' => Yii::t('app', 'Something went wrong'),
            'Files' => Yii::t('frontend/orderForm', 'Files'),
            'View File' => Yii::t('frontend/orderForm', 'View File'),
            'Upload/Replace File' => Yii::t('frontend/orderForm', 'Upload/Replace File'),
            'Upload/Replace Files' => Yii::t('frontend/orderForm', 'Upload/Replace Files'),
            'Submit' => Yii::t('app', 'Submit'),
            'The file was successfully updated' => Yii::t('app/models/file', 'The file was successfully updated'),
            'Order creator' => Yii::t('frontend/orderForm', 'Order creator'),
            'Recent status updated by' => Yii::t('frontend/orderForm', 'Recent status updated by'),
            'Printed Forms' => Yii::t('app/models/printedFormFormula', 'Printed Forms'),
            'Comment' => Yii::t('app/models/status', 'Comment'),
            'Fragments' => Yii::t('frontend/orderForm', 'Fragments'),
            "Drag 'n' drop some files here, or click to select files" => Yii::t(
                'app', 
                "Drag 'n' drop some files here, or click to select files"
            ),
            'The modules was successfully updated' => Yii::t('frontend/orderForm', 'The modules was successfully updated'),
            'By uploading a new version of module files you may remove previous module data for this order' => 
                Yii::t(
                    'frontend/orderForm', 
                    'By uploading a new version of module files you may remove previous module data for this order'
                ),
            'Modules' => Yii::t('frontend/orderForm', 'Modules'),
            'View Comments' => Yii::t('frontend/orderForm', 'View Comments'),
            'Reason' => Yii::t('app/models/status', 'Reason'),
            "You don't have permision to switch the order to previous status or this status doesn't exist" =>
                Yii::t(
                    'app/models/status',
                    "You don't have permision to switch the order to previous status or this status doesn't exist"
                )
        ];

        return $this->asJson(['translations' => $translations]);
    }

    public function actionProducts()
    {
        $products = Product::find()
            ->select('product.id, product.title_source_message_id, product_key')
            ->indexBy('product_key')
            ->with([
                'titleSourceMessage' => function ($query) {
                    $query->with([
                        'translations' => function ($query) {
                            $query->where(['language' => Yii::$app->language]);
                        }
                    ]);
                },
                'options' => function ($query) {
                    $query->with([
                        'titleSourceMessage' => function ($query) {
                            $query->with([
                                'translations' => function ($query) {
                                    $query->where(['language' => Yii::$app->language]);
                                }
                            ]);
                        },
                        'fileType' => function ($query) {
                            $query->with([
                                'titleSourceMessage' => function ($query) {
                                    $query->with([
                                        'translations' => function ($query) {
                                            $query->where(['language' => Yii::$app->language]);
                                        }
                                    ]);
                                }
                            ]);
                            $query->indexBy('option_key');
                        },
                    ]);
                    $query->indexBy('option_key');
                }
            ])
            ->asArray()
            ->all();
        
        return $this->asJson(['products' => $products]);
    }

    public function actionUser()
    {
        $currentUser = User::findOne(Yii::$app->user->id);
        return $this->asJson([
            'user' => [
                'fileAccessRules' => $currentUser->getFileAccessRules()
            ]
        ]);
    }

    public function actionPrintedForms()
    {
        $allPrintedForms = PrintedFormTemplate::find()
            ->select('id, title_source_message_id')
            ->with([
                'titleSourceMessage' => function ($query) {
                    $query->with([
                        'translations' => function ($query) {
                            $query->where(['language' => Yii::$app->language]);
                        }
                    ]);
                },
            ])
            ->asArray()
            ->all();
            
        return $this->asJson([
            'printedForms' => $allPrintedForms
        ]);
    }

    public function actionUsers()
    {
        $users = User::find()->select('id, full_name')->all();

        return $this->asJson(['users' => $users]);
    }

    public function actionClients()
    {
        $clients = Client::find()->select('id, full_name')->all();

        return $this->asJson(['clients' => $clients]);
    }
}
