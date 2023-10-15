<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use app\models\Order;
use app\models\Status;

class StatusLog extends ActiveRecord
{
    public $order_uuid;
     
    public function rules()
    {
        return [
            [
                [
                    'status_id', 
                    'order_id', 
                    'user_id', 
                    'order_uuid', 
                    'comment', 
                    'comment_reason_id',
                ],
                'safe'
            ],
            [['status_id', 'order_id', 'user_id'], 'required']
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

    public function attributeLabels()
    {
        return [
            'created_at' => Yii::t('app/models/status', 'Created At'),
        ];
    }

    public function search($params)
    {
        $this->load($params);
        $query = self::find();
        $query->joinWith('order');
        $query->andFilterWhere(['status_log.user_id' => $this->user_id]);
        $query->andFilterWhere(['order.uuid' => $this->order_uuid]);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function getCommentReason()
    {
        return $this->hasOne(StatusCommentReason::className(), ['id' => 'comment_reason_id']);
    }
}
