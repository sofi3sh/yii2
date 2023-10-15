<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use app\models\Product;
use app\models\User;
use app\models\OrderProductOption;
use app\models\Status;
use app\models\NextStatusOrder;
use app\models\StatusLog;
use app\models\File;
use yii\helpers\ArrayHelper;

class Order extends ActiveRecord
{
    public function rules()
    {
        return [
            [['client_id', 'user_id', 'product_id', 'status_id', 'is_deleted', 'allow_fragments'], 'safe'],
            [['client_id', 'product_id'], 'required'],
            [['client_id', 'user_id', 'product_id', 'status_id', 'is_deleted', 'allow_fragments'], 'integer'],
            ['uuid', 'unique'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];   
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->user_id = Yii::$app->user->identity->id;
        if (!$this->status_id) {
            $defaultStatus = Status::find()->where(['key' => Status::DRAFT])->one();
            $this->status_id = $defaultStatus->id;
        }
        return true;
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $this->generateUuid();
            $this->logStatusChange();
            
            return true;
        }
        return false;
    }

    public function attributeLabels()
    {
        return [
            'created_at' => Yii::t('app/models/order', 'Created At'),
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    public function getOrderProductOptions()
    {
        return $this->hasMany(OrderProductOption::className(), ['order_id' => 'id']);
    }

    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    public function getPreviousStatusLog()
    {
        return $this->hasOne(StatusLog::className(), ['order_id' => 'id'])
            ->orderBy(['created_at' => SORT_DESC])
            ->offset(1);
    }

    public function getPreviousStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id'])->via('previousStatusLog');
    }

    public function getStatusLog()
    {
        return $this->hasMany(StatusLog::className(), ['order_id' => 'id']);
    }
    
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['entity_id' => 'id']);
    }

    public function getModules() 
    {
        return $this->hasMany(OrderModule::className(), ['order_id' => 'id']);
    }

    public function generateUuid()
    {
        if ($this->uuid && !$this->id) {
            return false;
        }

        $uuidSuffix = 'w';
        $uuidNumber = str_pad((string)$this->id, 10, '0', STR_PAD_LEFT);
        $this->uuid = $uuidSuffix . $uuidNumber;
        $this->save();
        return true;
    }

    public function search($params)
    {
        $query = self::find();
        $query->where([
            'is_deleted' => 0
        ]);

        if (!isset($params['sort'])) {
            $query->orderBy([
                'created_at' => SORT_DESC,
            ]);
        }

        if (isset($params['search']) && $params['search']) {
            $allowedSearchColumns = [
                'id',
                'uuid',
                'user_id',
                'client_id',
                'status_id',
                'product_id'
            ];
            foreach ($params as $columnName => $columnValue) {
                if (in_array($columnName, $allowedSearchColumns)) {
                    $query->andFilterWhere([$columnName => $columnValue]);
                }
            }
        }

        $query->with([
            'product' => function ($query) {
                $query->select('id, title_source_message_id, product_key');
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
            'user' => function ($query) {
                $query->select('id, full_name');
            },
            'client' => function ($query) {
                $query->select('id, full_name');
            },
            'status' => function ($query) {
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
            'previousStatus' => function ($query) {
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
            'previousStatusLog' => function ($query) {
                $query->with([
                    'user' => function ($query) {
                        $query->select('id, full_name');
                    }
                ]);
            },
            'files'
        ]);
        $this->load($params);
        $query->asArray();

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page' => ($params && isset($params['page']) ? $params['page'] - 1 : 0)
            ]
        ]);
    }

    public function updateOrderProductOptions($updatedData)
    {
        $updatedData = json_decode($updatedData, true);
        if (empty($updatedData)) {
            return false;
        }
        $orderProductOptions = OrderProductOption::find()
            ->where([
                'order_id' => $this->id
            ])
            ->indexBy('product_option_id')
            ->all();
        $grilleType = ProductOption::find()
            ->where([
                'option_key' => ProductOption::HYDRAULIC_GRILLE_TYPE
            ])
            ->one();

        if ($updatedData[ProductOption::HYDRAULIC_GRILLE] == false && isset($orderProductOptions[$grilleType->id])) {
            $orderProductOptions[$grilleType->id]->delete();
            unset($orderProductOptions[$grilleType->id]);
        }

        if ($updatedData[ProductOption::HYDRAULIC_GRILLE] == true && !isset($orderProductOptions[$grilleType->id])) {
            $addedGrilleType = new OrderProductOption([
                'order_id' => $this->id,
                'product_option_id' => $grilleType->id,
                'product_option_value' => $updatedData[ProductOption::HYDRAULIC_GRILLE_TYPE]
            ]);
            $addedGrilleType->save();
            $orderProductOptions[$grilleType->id] = $addedGrilleType;
        }

        foreach($orderProductOptions as $orderProductOption) {
            $newOrderProductOptionValue = $updatedData[$orderProductOption->productOption->option_key];
            if ($orderProductOption->product_option_value !== $newOrderProductOptionValue) {
                $orderProductOption->setAttributes([
                    'product_option_value' => is_bool($newOrderProductOptionValue) ? json_encode($newOrderProductOptionValue) : $newOrderProductOptionValue
                ]);
            }
            $orderProductOption->save();
        }
    }

    public function switchToNextStatus($requestData)
    {
        $hasAccessToCurrentStatus = RoleStatus::checkAccess($this->id);
        if (!$hasAccessToCurrentStatus) {
            return false;
        }
        $nextStatusKey = $requestData['nextStatus'];
        $commentData = $requestData['comment'];
        $user = User::findOne(Yii::$app->user->id);
        $userRole = $user->getRole()->roleName;
        if (!$nextStatusKey) {
            $nextStatus = NextStatusOrder::find()
            ->where([
                'status_key' => $this->status->key,
                'user_role_name' => $userRole
            ])
            ->one();
        } else {
            $nextStatus = NextStatusOrder::find()
                ->where([
                    'next_status_key' => $nextStatusKey,
                    'status_key' => $this->status->key,
                    'user_role_name' => $userRole
                ])
                ->one();
        }

        if (!$nextStatus) {
            return false;
        }

        $isStatusUpdated = $this->updateAttributes([
            'status_id' => $nextStatus->nextStatus->id
        ]);
        if (!$isStatusUpdated) {
            return false;
        }
        $this->logStatusChange($commentData);
        return true;
    }

    public function switchToPreviousStatus()
    {
        $hasAccessToCurrentStatus = RoleStatus::checkAccess($this->id);
        if (!$hasAccessToCurrentStatus || !$this->previousStatus->id) {
            return false;
        }

        $hasAccessToPreviousStatus = RoleStatus::checkAccess($this->previousStatus->id);
        if (!$hasAccessToPreviousStatus) {
            return false;
        }

        $isStatusUpdated = $this->updateAttributes([
            'status_id' => $this->previousStatus->id
        ]);
        if (!$isStatusUpdated) {
            return false;
        }
        $this->logStatusChange();
        return true;
    }
    
    public function logStatusChange($commentData = [])
    {
        (new StatusLog([
            'order_id' => $this->id,
            'user_id' => Yii::$app->user->identity->id,
            'status_id' => $this->status_id,
            'comment' => isset($commentData['comment']) ? $commentData['comment'] : null,
            'comment_reason_id' => isset($commentData['reasonId']) ? $commentData['reasonId'] : null,
        ]))->save();
    }

    public function markAsDeleted()
    {
        $this->is_deleted = 1;
        return $this->save();
    }

    public function updateProductOptionFiles()
    {
        foreach ($this->files as $file) {
            $file->updateFileRecord(
                $file->orderProductOption->productOption->option_key
            );
        }
    }

    public static function getCurrentStatus($orderId)
    {   
        $order = Order::find()
            ->where(['id' => $orderId])
            ->one();

        if (!$order) {
            return false;
        }

        $hasAccessToCurrentStatus = RoleStatus::checkAccess($orderId);

        return $hasAccessToCurrentStatus ?
             Status::getStatusByKey($order->status->key) : Status::getStatusByKey(Status::IN_WORK);
    }
}
