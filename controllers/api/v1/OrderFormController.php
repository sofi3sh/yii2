<?php

namespace app\controllers\api\v1;

use Yii;
use app\controllers\api\v1\BaseController;
use \app\models\User;
use \app\models\Order;
use \app\models\Product;
use \app\models\ProductOption;
use \app\models\OrderProductOption;
use \app\models\Client;
use \app\models\File;
use \app\models\MeasurementSystem;
use \app\models\ProductProductOption;
use \app\models\RoleStatus;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\common\exceptions\ApiForbiddenHttpException;

class OrderFormController extends BaseController
{
    public $modelClass = 'app\models\Order';

    public function actions()
    {
        $defaultActions = parent::actions();
        unset($defaultActions['create']);
        unset($defaultActions['view']);
        unset($defaultActions['update']);
        unset($defaultActions['delete']);
        return $defaultActions;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'update' => ['PUT', 'POST'],
                ],
            ],
        ];
    }

    public function actionClients()
    {
        $clients = Client::find()->select('id, full_name')->all();

        return $this->asJson(['clients' => $clients]);
    }

    public function actionUser()
    {
        $currentUser = User::find()
            ->select('id, full_name')
            ->where(['id' => Yii::$app->user->id])
            ->with('client', 'settings')
            ->asArray()
            ->one();
        $measurementSystem = MeasurementSystem::MEASUREMENT_SYSTEMS_LIST[
            $currentUser['settings']['measurement_system_id']
        ];
        $currentUser['measurementSystem'] = $measurementSystem;
        return $this->asJson(['user' => $currentUser]);
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
                'parentOptions' => function ($query) {
                    $query->with([
                        'childrenOptions' => function ($query) {
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
                            $query->with([
                                'childrenOptions' => function ($query) {
                                    $query->indexBy('option_key');
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
                    ]);
                    $query->select('id, product_id, option_key, title_source_message_id, value, measurement_unit');
                    $query->indexBy('option_key');
                }
            ])
            ->asArray()
            ->all();
        
        return $this->asJson(['products' => $products]);
    }

    public function actionTranslations()
    {
        $translations = [
            'Order #' => Yii::t('frontend/orderForm', 'Order #'),
            'User' => Yii::t('frontend/orderForm', 'User'),
            'Client' => Yii::t('frontend/orderForm', 'Client'),
            'Product' => Yii::t('frontend/orderForm', 'Product'),
            'Previous' => Yii::t('frontend/orderForm', 'Previous'),
            'View File' => Yii::t('frontend/orderForm', 'View File'),
            'Allow Fragments' => Yii::t('frontend/orderForm', 'Allow Fragments'),
            'Next' => Yii::t('frontend/orderForm', 'Next'),
            'This field must contain a numeric value' => Yii::t('frontend/orderForm', 'This field must contain a numeric value'),
            'This field is required' => Yii::t('frontend/orderForm', 'This field is required'),
            'Create an order' => Yii::t('frontend/orderForm', 'Create an order'),
            'Tray Options' => Yii::t('frontend/orderForm', 'Tray Options'),
            'Drainage Options' => Yii::t('frontend/orderForm', 'Drainage Options'),
            'Grate Options' => Yii::t('frontend/orderForm', 'Grate Options'),
            'Bridge Tray Options' => Yii::t('frontend/orderForm', 'Bridge Tray Options'),
            'Save The Order' => Yii::t('frontend/orderForm', 'Save The Order'),
            'The changes were successfully saved' => Yii::t('frontend/orderForm', 'The changes were successfully saved'),
            'Something went wrong' => Yii::t('app', 'Something went wrong'),
            'List of orders' => Yii::t('app/models/order', 'List of orders'),
            'This field must be between {value} and {value} values' => Yii::t(
                'frontend/orderForm', 
                'This field must be between {value} and {value} values'
            ),
            'You should select either the {value} field or the {value} field but not both fields at the same time' => 
                Yii::t(
                    'frontend/orderForm', 
                    'You should select either the {value} field or the {value} field but not both fields at the same time'
                ),
            'This field must be a multiple of 0.5' => Yii::t('frontend/orderForm', 'This field must be a multiple of 0.5'),
            'You can select only two checkboxes out of three at a time' => Yii::t(
                'frontend/orderForm', 
                'You can select only two checkboxes out of three at a time'
            ),
            'Calculate' => Yii::t('frontend/orderForm', 'Calculate'),
            '(measured in {value})' => Yii::t('frontend/orderForm', '(measured in {value})'),
            '(measured in %)' => Yii::t('frontend/orderForm', '(measured in %)'),
            'Make sure you specify the length of the channel and fill in one of three fields:<br>Length, H min, H max' => Yii::t(
                'instruction', 
                'Make sure you specify the length of the channel and fill in one of three fields:<br>Length, H min, H max'
            ),
            'Overall line length = tray length + ladder gauge (if selected) + end lid gauge' => Yii::t(
                'instruction',
                'Overall line length = tray length + ladder gauge (if selected) + end lid gauge'
            ),
            'You must select either the {value} field or the {value} field but not both fields at the same time' =>
               Yii::t(
                   'frontend/orderForm', 
                   'You must select either the {value} field or the {value} field but not both fields at the same time'
                ),
            'Close' => Yii::t('app', 'Close'), 
        ];

        return $this->asJson(['translations' => $translations]);
    }

    public function actionCreate()
    {
        $requestData = Yii::$app->request->post();
        $product = Product::find()
            ->where([
                'product_key' => $requestData['product_key']
            ])
            ->one();
        $newOrder = new Order([
            'client_id' => $requestData['client_id'],
            'product_id' => $product->id,
            'allow_fragments' => $requestData['allow_fragments'],
        ]);

        if (!$newOrder->validate()) {
            Yii::$app->response->statusCode = 400;
            return $this->asJson([
                'success' => false,
                'errors' => $newOrder->getErrors()
            ]);
        }
        $newOrder->save();
        
        OrderProductOption::saveOrderProductOptions($requestData[$product->product_key], $newOrder->id);
        OrderProductOption::saveFilesOrderProductOptions($product->product_key, $newOrder->id);

        Yii::$app->response->statusCode = 200;
        return $this->asJson([
            'success' => true,
            'errors' => []
        ]);
    }

    public function actionView($id)
    {
        $order = Order::find()->with([
            'product' => function ($query) {
                $query->with([
                    'titleSourceMessage' => function ($query) {
                        $query->with([
                            'translations' => function ($query) {
                                $query->where(['language' => Yii::$app->language]);
                            }
                        ]);
                    },
                ]);
            },
        ])
        ->where(['id' => $id])
        ->asArray()
        ->one();
        
        $mappedOrderProductOptions = OrderProductOption::mapProductOptionsWithValues($id);

        return $this->asJson([
            'order' => [
                'allow_fragments' => $order['allow_fragments'],
                'client_id' => $order['client_id'],
                'product' => $order['product'],
                'orderProductOptions' => $mappedOrderProductOptions
            ]
        ]);
    }

    public function actionUpdate()
    {
        $requestData = Yii::$app->request->post();
        $hasAccess = RoleStatus::checkAccess($requestData['id']);
        if (!$hasAccess) {
            throw new ApiForbiddenHttpException();
        }
        $order = Order::find()->with()
            ->where(['id' => $requestData['id']])
            ->one();
        
        $order->setAttributes($requestData);
        $order->save();
        if (isset($requestData[$order->product->product_key])) {
           $order->updateOrderProductOptions($requestData[$order->product->product_key]); 
        }
        $order->updateProductOptionFiles();
        OrderProductOption::saveFilesOrderProductOptions($order->product->product_key, $order->id);

        Yii::$app->response->statusCode = 200;

        return $this->asJson([
            'success' => true,
            'errors' => []
        ]);
    }

    public function actionDelete()
    {
        $requestData = json_decode(Yii::$app->request->getRawBody(), true);
        $hasAccess = RoleStatus::checkAccess($requestData['id']);
        if (!$hasAccess) {
            throw new ApiForbiddenHttpException();
        }
        $order = Order::find()->with()
            ->where(['id' => $requestData['id']])
            ->one();
        $order->markAsDeleted();
        
        Yii::$app->response->statusCode = 200;
        
        return $this->asJson([
            'success' => true,
            'errors' => []
        ]);
    }

    public function actionFiles($orderId = null)
    {
        $orderFiles = [];
        if ($orderId) {
            $orderFiles = File::find()
            ->with('productOption')
            ->where([
                'entity_id' => $orderId
            ])
            ->indexBy('productOption.option_key')
            ->asArray()
            ->all();
        }
        
        return $this->asJson([
            'files' => [
                'orderFiles' => $orderFiles
            ]
        ]);
    }

    public function actionDynamicOptions($productId) 
    {
        $options = ProductProductOption::getDynamicOptionsForProduct($productId);

        return $this->asJson([
            'dynamicOptions' => $options
        ]);
    }
}
