<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\common\traits\SourceMessage;
use app\common\validators\TranslationValidator;
use yii\data\ActiveDataProvider;
use app\models\ProductOption;
use app\models\ProductProductOption;
use app\common\validators\CyrillicValidator;
use app\common\validators\AttributeNotChanged;

class Product extends ActiveRecord
{
    use SourceMessage;
    
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_CREATE = 'create';

    const INTERNAL_DRAINAGE = 'internal_drainage';
    const BRIDGE_TRAY = 'bridge_tray';

    const INTERNAL_DRAINAGE_ID = 1;
    
    public $productOptions;
    public $translations;
    public $cacheId;
    public $relations = [
        'title_source_message_id' => 'titleSourceMessage'
    ];

    public function rules()
    {
        return [
            [['title_source_message_id', 'translations', 'product_key'], 'safe'],
            [
                ['title_source_message_id'], 
                TranslationValidator::className(), 
                'skipOnEmpty' => false
            ],
            [['product_key'], 'required'],
            [['product_key'], CyrillicValidator::className()],
            [['product_key'], AttributeNotChanged::className(), 'on' => self::SCENARIO_UPDATE],
            [['product_key'], 'unique'],
            [['translations'], 'validateTranslations'],
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
            'product',
            '\app\models\ProductSourceMessage',
            '\app\models\ProductMessage'
        );
        return true;
    }

        
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        $productOptionsData = Yii::$app->request->post('Product')['productOptions'];
        ProductProductOption::saveProductOptionRelations($productOptionsData, $this->id);
    }

    public function beforeDelete()
    {
        $this->cacheId = $this->id;
        return parent::beforeDelete();
    }

    public function afterDelete()
    {
        ProductOption::deleteAll(
            'product_id = :product_id', 
            ['product_id' => $this->cacheId]
        );
        parent::afterDelete();
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app/models/product', 'Name'),
            'created_at' => Yii::t('app/models/product', 'Created At'),
            'title_source_message_id' => Yii::t('app/models/product', 'Name'),
            'product_key' => Yii::t('app/models/product', 'Product Key'),
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['title'] = 'title';
        return $fields;
    }

    public function validateTranslations($attribute, $params, $validator)
    {
        $englishLanguage = Language::LANGUAGES_LIST[Language::EN_ID];
        if (!isset($this->translations['title_source_message_id'][$englishLanguage['code']])) {
            return true;
        }

        if (CyrillicValidator::isCyrillicCharacters($this->translations['title_source_message_id'][$englishLanguage['code']])) {
            $this->addError(
                $attribute,
                Yii::t(
                    'validation', 
                    'Attribute {attribute} can not contain Cyrillic characters', 
                    [
                        'attribute' => $englishLanguage['title'] . ' - ' . Yii::t('app/models/product', 'Name')
                    ]
                ) 
            );
        }
    }

    public function getProductMessage()
    {
        return $this->hasOne(ProductMessage::className(), ['id' => 'title_source_message_id']);
    }

    public function getProductOption()
    {
        return $this->hasMany(ProductOption::className(), ['product_id' => 'id']);
    }

    public function getProductOptionsArray()
    {
        return ProductProductOption::find()
            ->where(['product_id' => $this->id])
            ->asArray()
            ->all();
    }

    public function getTitleSourceMessage()
    {
        return $this->hasOne(ProductSourceMessage::className(), ['id' => 'title_source_message_id']);
    }

    public function getTitle()
    {
        return Yii::t('product', $this->titleSourceMessage->message);
    }

    public function search($params)
    {
        $query = self::find();
        $this->load($params);

		return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
    
    public function getParentOptions()
    {
        return $this->hasMany(ProductOption::className(), ['product_id' => 'id'])->andOnCondition(['parent_id' => null]);
    }
    
    public function getOptions()
    {
        return $this->hasMany(ProductOption::className(), ['product_id' => 'id']);
    }
}
