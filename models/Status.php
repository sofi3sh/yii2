<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use app\common\traits\SourceMessage;
use app\models\StatusSourceMessage;
use app\common\validators\TranslationValidator;
use app\common\validators\CyrillicValidator;
use app\models\NextStatusOrder;

class Status extends ActiveRecord
{
    use SourceMessage;

    public $translations;
    public $next_statuses;
    public $relations = [
        'title_source_message_id' => 'titleSourceMessage'
    ];

    const DRAFT = 'draft';
    const TS = 'ts';
    const CHECK_TECH = 'check_tech';
    const OFFER = 'offer';
    const OFFER_FORMED = 'offer_formed';
    const CANCEL = 'cancel';
    const REQUEST_TS = 'request_ts';
    const CORRECTION_TT = 'correction_tt';
    const CORRECTION_TECH = 'correction_tech';
    const OFFER_APPROVAL = 'offer_approval';
    const DISCOUNT_ANSW = 'discount_answ';
    const DISCOUNT_REQUEST = 'discount_request';
    const IN_WORK = 'in_work';

    public function rules()
    {
        return [
            [['key', 'color', 'order', 'title_source_message_id', 'translations', 'allow_comment'], 'safe'],
            [
                ['title_source_message_id'], 
                TranslationValidator::className(), 
                'skipOnEmpty' => false
            ],
            [['order', 'key'], 'required'],
            [
                'key', 
                'match', 
                'pattern' => '/[\s#\/+%$-]/', 
                'message' => Yii::t(
                    'validation', 
                    'Attribute {attribute} can not contain non-word characters',
                    ['attribute' => $this->getAttributeLabel('key')]
                ), 
                'not' => true
            ],
            ['key', CyrillicValidator::className()],
            [['key', 'order'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app/models/status', 'Name'),
            'title_source_message_id' => Yii::t('app/models/status', 'Name'),
            'key' => Yii::t('app/models/status', 'Key'),
            'color' => Yii::t('app/models/status', 'Сolor'),
            'order' => Yii::t('app/models/status', 'Order'),
            'created_at' => Yii::t('app/models/status', 'Created At'),
            'allow_comment' => Yii::t('app/models/status', 'Allow Comment'),
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
        $this->key = strtolower($this->key);
        $this->menageFieldTranslations(
            'title_source_message_id', 
            $this->translations['title_source_message_id'],
            'status',
            '\app\models\StatusSourceMessage',
            '\app\models\StatusMessage'
        );
        return true;
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }
    
        NextStatusOrder::deleteAll(['next_status_key' => $this->key]);
        return true;
    }

    public function search($params)
    {
        $query = self::find();
        $this->load($params);

		return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function getTitleSourceMessage()
    {
        return $this->hasOne(StatusSourceMessage::className(), ['id' => 'title_source_message_id']);
    }

    public function getTitle()
    {
        return Yii::t('status', $this->titleSourceMessage->message);
    }

    public function getNextStatuses()
    {
        return $this->hasMany(NextStatusOrder::className(), ['status_key' => 'key']);
    }

    public function getCommentReasons()
    {
        return $this->hasMany(StatusCommentReason::className(), ['status_id' => 'id']);
    }

    public static function getStatusByKey($statusKey)
    {
        return self::find()
            ->where(['key' => $statusKey])
            ->with([
                'titleSourceMessage' => function ($query) {
                    $query->with([
                        'translations' => function ($query) {
                            $query->where(['language' => Yii::$app->language]);
                        }
                    ]);
                }
            ])
            ->asArray()
            ->one();
    }
}
