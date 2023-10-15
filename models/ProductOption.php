<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use \app\models\ProductSourceMessage;
use \app\models\Product;
use \app\models\FileType;
use \app\models\PrintedFormFormula;
use app\common\traits\SourceMessage;
use app\common\validators\TranslationValidator;
use app\common\validators\CyrillicValidator;
use app\common\validators\AttributeNotChanged;
use yii\data\ActiveDataProvider;

class ProductOption extends ActiveRecord
{
    use SourceMessage;

    const SCENARIO_UPDATE = 'update';
    const SCENARIO_CREATE = 'create';

    const HYDRAULIC_SPLIT = 'hydraulic_split';
    const EURO_100 = 'euro_100';
    const EURO_200 = 'euro_200';
    const HYDRAULIC_TRAY_LENGTH = 'hydraulic_tray_length';
    const HYDRAULIC_TRAY_SLOPE = 'hydraulic_tray_slope';
    const HEIGHT_MIN = 'height_min';
    const HEIGHT_MAX = 'height_max';
    const EURO_100_HEIGHT_MIN = 'euro_100_height_min';
    const EURO_200_HEIGHT_MIN = 'euro_200_height_min';
    const EURO_100_HEIGHT_MAX = 'euro_100_height_max';
    const EURO_200_HEIGHT_MAX = 'euro_200_height_max';
    const HYDRAULIC_CONNECTION_TYPE = 'hydraulic_connection_type';
    const HYDRAULIC_FLANGE = 'hydraulic_flange';
    const HYDRAULIC_UNDER_WELDING = 'hydraulic_under_welding';
    const HYDRAULIC_DRAINAGE_TYPE = 'hydraulic_drainage_type';
    const HYDRAULIC_TUBULAR_OUTPUT = 'hydraulic_tubular_output';
    const HYDRAULIC_LADDER_UNDER_CUT = 'hydraulic_ladder_under_cut';
    const HYDRAULIC_TRAPOPRUYAMOK = 'hydraulict_trapopruyamok';
    const HYDRAULIC_RELEASE_DIRECTION = 'hydraulic_release_direction';
    const HYDRAULIC_RELEASE_DIRECTION_LEFT = 'hydraulic_release_direction_left';
    const HYDRAULIC_RELEASE_DIRECTION_RIGHT = 'hydraulic_release_direction_right';
    const HYDRAULIC_RELEASE_DIRECTION_STRAIGHT = 'hydraulic_release_direction_straight';
    const HYDRAULIC_RELEASE_DIRECTION_VERTICALLY = 'hydraulic_release_direction_vertically';
    const HYDRAULIC_RELEASE_PLACEMENT = 'hydraulic_release_placement';
    const HYDRAULIC_RELEASE_PLACEMENT_END = 'hydraulic_release_placement_end';
    const HYDRAULIC_WATER_SEAL = 'hydraulic_water_seal';
    const HYDRAULIC_WATER_SEAL_AND_CATCHER = 'hydraulic_water_seal_and_catcher';
    const HYDRAULIC_GRILLE = 'hydraulic_grille';
    const HYDRAULIC_GRILLE_TYPE = 'hydraulic_grille_type';
    const HYDRAULIC_GRILLE_TYPE_PERFORATED = 'hydraulic_grille_type_perforated';
    const HYDRAULIC_GRILLE_TYPE_CELLULAR = 'hydraulic_grille_type_cellular';
    const HYDRAULIC_GRILLE_TYPE_SLIT = 'hydraulic_grille_type_slit';
    const HYDRAULIC_GRILLE_TYPE_CAST_IRON = 'hydraulic_grille_type_cast_iron';
    const HYDRAULIC_GRILLE_TYPE_NONSTANDARD = 'hydraulic_grille_type_non_standard';
    const HYDRAULIC_GRILLE_TYPE_WITHOUT_GRILLE = 'hydraulic_grille_type_without_grille';
    const BRIDGE_TRAY_FILE = 'bridge_tray_file';
    const HYDRAULIC_GRILLE_NON_STANDARD_FILE = 'hydraulic_grille_non_standard_file';
    const HYDRAULIC_GRILLE_ADJUSTMENT_CUSTOMER = 'hydraulic_grille_adjustment_by_customer';
    const HYDRAULIC_GRILLE_ADJUSTMENT_MANUFACTURE = 'hydraulic_grille_adjustment_by_manufacturer';
    const HYDRAULIC_GRILLE_MASH_ANTI_SLIP = 'hydraulic_grille_mash_anti_slip';
    const HYDRAULIC_GRILLE_LAMELLAR = 'hydraulic_grille_lamellar';
    const NO_END_LID_IN_BEGINNING_OF_LINE = 'no_end_lid_in_beginning_of_line';
    const OUTFALL_DIAMETER = 'Outfall_diametr';

    const DROPDOWN_CHILD_OPTION_KEY = 4;

    const ACTION_DELETE = 'delete';
    const ACTION_UPDATE = 'update';
    const ACTION_CREATE = 'create';

    const IS_DYNAMIC = 1;

    const FIRST_IN_FORM_KEY = self::HYDRAULIC_SPLIT;

    public $translations;

    const OPTION_TYPES = [
        1 => 'input',
        2 => 'checkbox',
        3 => 'dropdown',
        4 => 'dropdown option',
        5 => 'file'
    ];

    const LENGTH_FIELDS_TO_CONVERT = [
        ProductOption::HYDRAULIC_TRAY_LENGTH,
        ProductOption::HEIGHT_MIN,
        ProductOption::HEIGHT_MAX,
    ];

    public function rules()
    {
        return [
            [[
                'title_source_message_id',
                'parent_id',
                'product_id',
                'option_type',
                'option_key',
                'value',
                'translations',
                'measurement_unit',
                'previous_option_id',
                'is_dynamic'
            ], 'safe'],
            [['product_id', 'option_type', 'option_key'], 'required'],
            [['title_source_message_id', 'product_id', 'parent_id', 'option_type'], 'integer'],
            [['option_key'], 'trim'],
            [['option_key'], AttributeNotChanged::className(), 'on' => self::SCENARIO_UPDATE],
            [['option_key', 'measurement_unit'], CyrillicValidator::className()],
            [['option_key'], 'unique'],
            ['option_key', 'string', 'length' => [4, 100]],
            [['option_type'], 'validateOptionType'],
            [
                ['title_source_message_id'],
                TranslationValidator::className(),
                'skipOnEmpty' => false
            ],
            [['is_dynamic'], 'validateSystemOptionDisplaying']
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

        $this->createFieldTranslations(
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
        if (!$insert) {
            $this->updateOptionPosition($changedAttributes);
            return true;
        }

        $this->createFormulaForOption();
        $this->insertOptionPosition();
        return true;
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        $this->removeOptionPosition();
        OrderProductOption::deleteAll(['product_option_value' => $this->option_key]);
        return true;
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['title'] = 'title';
        return $fields;
    }

    public function getTitleSourceMessage()
    {
        return $this->hasOne(ProductSourceMessage::className(), ['id' => 'title_source_message_id']);
    }

    public function getProductMessage()
    {
        return $this->hasOne(ProductMessage::className(), ['id' => 'title_source_message_id']);
    }

    public function getTitle()
    {
        return Yii::t('product', $this->titleSourceMessage->message);
    }

    public function getTitleWithOptionKey()
    {
        return $this->title . ' (' . $this->option_key . ')';
    }

    public function getChildrenOptions()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getFileType()
    {
        return $this->hasOne(FileType::className(), ['key' => 'option_key']);
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app/models/productOption', 'Name'),
            'parent_id' => Yii::t('app/models/productOption', 'Parent'),
            'product_id' => Yii::t('app/models/productOption', 'Product'),
            'option_key' => Yii::t('app/models/productOption', 'Option Key'),
            'option_type' => Yii::t('app/models/productOption', 'Option Type'),
            'value' => Yii::t('app/models/productOption', 'Value'),
            'measurement_unit' => Yii::t('app/models/productOption', 'Measurement Unit'),
            'is_dynamic' => Yii::t('app/models/productOption', 'Dynamic'),
            'previous_option_id' => Yii::t('app/models/productOption', 'Display after')
        ];
    }

    public function search($params)
    {
        if (isset($params['sort'])) {
            $sortColumn = ltrim($params['sort'], '-');
        }

        if (isset($sortColumn) && $sortColumn === 'product_title') {
            $query = self::find()->select('product_option.*')
                ->leftJoin('product', 'product_option.product_id = product.id')
                ->leftJoin('product_source_message', 'product_source_message.id = product.title_source_message_id')
                ->leftJoin('product_message', 'product_message.id = product_source_message.id');
        } else {
            $query = $this->getTranslationsQuery('productMessage');
        }

        if (Yii::$app->language !== 'en-US') {
            $query->andFilterWhere(['language' => Yii::$app->language]);
        }

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'option_key',
                'created_at',
                'title' => [
                    'asc' => $this->getDbSortingCondition(SORT_ASC),
                    'desc' => $this->getDbSortingCondition(SORT_DESC)
                ],
                'product_title' => [
                    'asc' => $this->getDbSortingCondition(SORT_ASC),
                    'desc' => $this->getDbSortingCondition(SORT_DESC)
                ]
            ]
        ]);

        return $dataProvider;
    }

    public static function getOptionTypeByName($name)
    {
        return array_search($name, self::OPTION_TYPES);
    }

    public function getOptionFormPositions()
    {
        $result = [];
        $previousOption = ProductOption::find()
            ->where([
                'id' => $this->previous_option_id,
                'is_dynamic' => ProductOption::IS_DYNAMIC,
                'product_id' => $this->product_id
            ])
            ->one();

        if ($previousOption) {
            $nextOption = ProductOption::find()
            ->where([
                'previous_option_id' => $previousOption->id,
                'is_dynamic' => ProductOption::IS_DYNAMIC,
                'product_id' => $this->product_id
            ])
            ->andWhere(['not', ['id' => $this->id]])
            ->one();

            $result['previous'] = $previousOption;
            $result['next'] = $nextOption;
        }

        return $result;
    }

    public function insertOptionPosition()
    {
        if ($this->option_type == self::DROPDOWN_CHILD_OPTION_KEY || !$this->is_dynamic) {
            return;
        }

        $nextOption = $this->getOptionFormPositions()['next'];
        if ($nextOption) {
            $nextOption->updateAttributes(['previous_option_id' => $this->id]);
        }
    }

    public function updateOptionPosition($changedAttributes)
    {
        if ($this->option_type == self::DROPDOWN_CHILD_OPTION_KEY) {
            return;
        }

        list(
            'previous' => $previousOption,
            'next' => $nextOption
        ) = $this->getOptionFormPositions();

        $nextRelatedToCurrentOption = ProductOption::find()
            ->where([
                'previous_option_id' => $this->id,
                'is_dynamic' => ProductOption::IS_DYNAMIC,
                'product_id' => $this->product_id
            ])
            ->one();

        if (isset($changedAttributes['is_dynamic'])) {
            if ($this->is_dynamic == 0 && $changedAttributes['is_dynamic'] == 1) {
                $this->updateAttributes(['previous_option_id' => NULL]);
                if ($nextRelatedToCurrentOption) {
                    $nextRelatedToCurrentOption->previous_option_id = $previousOption->id;
                    $nextRelatedToCurrentOption->updateAttributes(['previous_option_id']);
                }
                return;
            }
    
            if ($this->is_dynamic == 1 && $changedAttributes['is_dynamic'] == 0) {
                if ($nextOption) {
                    $nextOption->previous_option_id = $this->id;
                    $nextOption->updateAttributes(['previous_option_id']);
                }
                return;
            }
        }

        $oldPreviousOption = ProductOption::find()
            ->where([
                'id' => $changedAttributes['previous_option_id'],
                'product_id' => $this->product_id,
                'is_dynamic' => ProductOption::IS_DYNAMIC
            ])
            ->one();

        if (!$oldPreviousOption) {
            $this->updateAttributes(['previous_option_id' => NULL]);
        }

        $oldNextOption = ProductOption::find()
            ->where([
                'previous_option_id' => $this->id,
                'product_id' => $this->product_id,
                'is_dynamic' => ProductOption::IS_DYNAMIC
            ])
            ->one();

        $transaction = ProductOption::getDb()->beginTransaction();
        try {
            if ($oldNextOption) {
                $oldNextOption->previous_option_id = $oldPreviousOption->id;
                $oldNextOption->updateAttributes(['previous_option_id']);
            }
    
            if ($nextOption) {
                $nextOption->previous_option_id = $this->id;
                $nextOption->updateAttributes(['previous_option_id']);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->addError(
                'is_dynamic',
                Yii::t('app', 'Something went wrong')
            );
        }
    }

    public function removeOptionPosition()
    {
        if ($this->option_type == self::DROPDOWN_CHILD_OPTION_KEY || !$this->is_dynamic) {
            return;
        }
        
        list(
            'previous' => $previousOption,
            'next' => $nextOption
        ) = $this->getOptionFormPositions();

        $nextOption = ProductOption::find()
            ->where([
                'previous_option_id' => $this->id,
                'is_dynamic' => ProductOption::IS_DYNAMIC,
                'product_id' => $this->product_id
            ])
            ->one();

        if (!$nextOption) {
            return;
        }

        if (!$this->previous_option_id) {
            $nextOption->previous_option_id = NULL;
        }
        $nextOption->previous_option_id = $previousOption->id;
        $nextOption->updateAttributes(['previous_option_id']);
    }

    public function validateOptionType()
    {
        if (($this->option_type === self::DROPDOWN_CHILD_OPTION_KEY) && $this->is_dynamic) {
            $this->addError(
                'option_type',
                Yii::t('validation', 'You can\'t use dropdown option in order creation form')
            );
        }
    }

    public function validateSystemOptionDisplaying()
    {
        if ($this->is_system && !$this->is_dynamic) {
            $this->addError(
                'is_dynamic',
                Yii::t('validation', 'You can\'t remove system options from order creation form')
            );
        }

        if ($this->is_dynamic && !$this->previous_option_id && $this->option_key !== ProductOption::FIRST_IN_FORM_KEY) {
            $this->addError(
                'previous_option_id',
                Yii::t('validation', 'You must specify previous option for dynamic ones')
            );
        }
    }

    public function createFormulaForOption()
    {
        $formula = new PrintedFormFormula();
        $formula->translations = $this->translations;
        $formula->key = "product_option_$this->option_key";
        $formula->expression = $formula->getExpression('option.' . $this->option_key);
        $formula->is_system = 1;

        $formula->save();
    }
    
    public static function getAvailablePreviousOptions($availableOptions, $currentOption)
    {
        $reindexedOptions = [];

        foreach ($availableOptions as $option) {
            if ($option->option_key !== $currentOption->option_key) {
                $reindexedOptions[$option->id] = $option;
            }
        };

        return $reindexedOptions;
    }
}
