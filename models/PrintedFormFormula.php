<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use app\common\traits\SourceMessage;
use app\common\validators\TranslationValidator;
use app\common\validators\CyrillicValidator;

class PrintedFormFormula extends ActiveRecord
{
    use SourceMessage;

    public $translations;
    public $relations = [
        'title_source_message_id' => 'titleSourceMessage'
    ];

    const KEY_ORDER_UUID = 'order_uuid';
    const KEY_CLIENT_NAME = 'client_full_name';
    const KEY_CLIENT_PHONE = 'client_phone';
    const KEY_CLIENT_ADDRESS_LEGAL = 'client_address_legal';
    const KEY_CLIENT_ADDRESS_ACTUAL = 'client_address_actual';
    const KEY_USER_NAME = 'user_full_name';
    const KEY_PRODUCT_TITLE = 'product_title';
    const KEY_HYDRAULIC_SPLIT = 'product_option_hydraulic_split';
    const KEY_HEIGHT_MIN = 'product_option_height_min';
    const KEY_HEIGHT_MAX = 'product_option_height_max';
    const KEY_HYDRAULIC_TRAY_LENGTH = 'product_option_hydraulic_tray_length';
    const KEY_HYDRAULIC_TRAY_SLOPE = 'product_option_hydraulic_tray_slope';
    const KEY_HYDRAULIC_CONNECTION_TYPE = 'product_option_hydraulic_connection_type';
    const KEY_HYDRAULIC_DRAINAGE_TYPE = 'product_option_hydraulic_drainage_type';
    const KEY_HYDRAULIC_RELEASE_DIRECTION = 'product_option_hydraulic_release_direction';
    const KEY_HYDRAULIC_RELEASE_PLACEMENT = 'product_option_hydraulic_release_placement';
    const KEY_HYDRAULIC_GRILLE_TYPE = 'product_option_hydraulic_grille_type';
    const KEY_HYDRAULIC_GRILLE_ADJUSTMENT_BY_CUSTOMER = 'product_option_hydraulic_grille_adjustment_by_customer';
    const KEY_HYDRAULIC_GRILLE_ADJUSTMENT_BY_MANUFACTURER = 'product_option_hydraulic_grille_adjustment_by_manufacturer';

    public function rules()
    {
        return [
            [
                [
                    'title_source_message_id',
                    'translations',
                    'key',
                    'expression',
                    'is_system'
                ],
                'safe'
            ],
            [
                ['title_source_message_id'], 
                TranslationValidator::className(), 
                'skipOnEmpty' => false
            ],
            [['key', 'expression'], 'required'],
            [['key'], CyrillicValidator::className()],
            [['key'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => Yii::t('app', 'Mark'),
            'expression' => Yii::t('app', 'Formula'),
            'is_system' => Yii::t('app/models/printedFormFormula', 'System variable'),
            'created_at' => Yii::t('app/models/printedFormFormula', 'Created At'),
            'title_source_message_id' => Yii::t('app', 'Title'),
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

        $this->menageFieldTranslations(
            'title_source_message_id', 
            $this->translations['title_source_message_id'],
            'printedForm',
            '\app\models\PrintedFormSourceMessage',
            '\app\models\PrintedFormMessage'
        );
        return true;
    }

    public function getTitleSourceMessage()
    {
        return $this->hasOne(PrintedFormSourceMessage::className(), ['id' => 'title_source_message_id']);
    }

    public function getPrintedFormMessage()
    {
        return $this->hasOne(PrintedFormMessage::className(), ['id' => 'title_source_message_id']);
    }

    public function getTitle()
    {
        return Yii::t('printedForm', $this->titleSourceMessage->message);
    }

    public function getExpression($value)
    {
        return '{' . $value . '}';
    }

    public function getQueryWithFilters($params) {
        $query = isset($params['name']) ?
             $this->getTranslationsQuery('printedFormMessage') :
             self::find();

        if (!isset($params)) {
            return $query;
        }

        $filterableColumns = ['key', 'expression'];
    
        foreach($params as $key => $value) {
            if ($value && in_array($key, $filterableColumns)) {
                $query->andWhere(['like', $key, '%'. $value . '%', false]);
            }

            if ($key !== 'name') {
                continue;
            }

            $key = Yii::$app->language === 'en-US' ? 'message' : 'translation';
            $query->andWhere(['like', $key, '%'. $value . '%', false]);
            
            if (!Yii::$app->language === 'en-US') {
                $query->andFilterWhere(['language' => Yii::$app->language]);
            }
        }

        return $query;
    }

    public function search($params)
    {
        $params = isset($params['PrintedFormFormula']) ?
             $params['PrintedFormFormula'] : null;

        $query = $this->getQueryWithFilters($params);

        $this->load($params);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}
