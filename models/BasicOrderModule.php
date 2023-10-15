<?php

namespace app\models;

use \Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
use yii\web\BadRequestHttpException;
use app\common\traits\OrderModules;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use app\common\validators\AttributeNotChanged;

class BasicOrderModule extends ActiveRecord
{
    use OrderModules;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public $moduleComponents = [];

    public function rules()
    {
        return [
            [[
                'title',
                'amount',
                'weight',
                'material',
                'laser',
                'bending',
                'welding'
            ], 'required'],
            [[
                'amount',
                'weight',
                'laser',
                'bending',
                'welding'
            ], 'number'],
            [['created_at'], 'default', 'value' => 'NOW()'],
            [
                [
                    'amount',
                    'weight',
                    'material',
                    'laser',
                    'bending'
                ], AttributeNotChanged::className(), 'when' => function ($model) {
                    return !$model->module_id;
                }, 'on' => self::SCENARIO_UPDATE
            ]
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

    public function search($params)
    {
        $query = self::find();
        $this->load($params);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app/models/basicOrderModule', 'Title'),
            'amount' => Yii::t('app/models/basicOrderModule', 'Amount'),
            'weight' => Yii::t('app/models/basicOrderModule', 'Weight'),
            'material' => Yii::t('app/models/basicOrderModule', 'Material'),
            'laser' => Yii::t('app/models/basicOrderModule', 'Laser'),
            'bending' => Yii::t('app/models/basicOrderModule', 'Bending'),
            'welding' => Yii::t('app/models/basicOrderModule', 'Welding'),
            'created_at' => Yii::t('app/models/basicOrderModule', 'Created At')
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert && $this->module_id) {
            $this->calculateModuleAttributes();
        }
    }

    public function calculateModuleAttributes()
    {
        $fragmentSiblings = self::find()
            ->where(['module_id' => $this->module_id])
            ->all();

        $fragmentModule = self::find()
            ->where(['id' => $this->module_id])
            ->one();

        if (!$fragmentModule) {
            return false;
        }

        $calculatedData = [
            'weight' => 0,
            'amount' => 0,
            'material' => '',
            'laser' => 0,
            'bending' => 0
        ];

        foreach ($fragmentSiblings as $fragment) {
            $calculatedData['weight'] += $fragment->weight;
            $calculatedData['amount'] += $fragment->amount;
            $calculatedData['material'] = $fragment->material;
            $calculatedData['laser'] += $fragment->laser;
            $calculatedData['bending'] += $fragment->bending;
        }

        $fragmentModule->updateAttributes($calculatedData);
    }

    public function saveParsedModules()
    {
        $moduleTitle = $this->getBasicModuleTitle();
        $existingBasicModule = BasicOrderModule::find()
            ->where(['title' => $moduleTitle])
            ->one();

        if ($existingBasicModule) {
            Yii::$app->session->setFlash(
                'error',
                Yii::t(
                    'app/models/basicOrderModule',
                    'Item with this title already exists, name must be unique'
                )
            );

            return false;
        }

        $parsedModuleData = $this->getModuleParsedStaticData(true);
        $calculatedData = [
            'weight' => 0,
            'amount' => 0,
            'material' => '',
            'laser' => 0,
            'bending' => 0
        ];

        $moduleFragments = [];

        foreach ($parsedModuleData as $module) {
            $fragmentCalculatedData = [
                'title' => '',
                'weight' => 0,
                'amount' => 0,
                'material' => '',
                'laser' => 0,
                'bending' => 0,
                'welding' => 0
            ];
            foreach ($module['components'] as $component) {
                $cellIterator = $component->getCellIterator();
                foreach ($cellIterator as $columnName => $cell) {
                    $cellValue = $cell->getValue();
                    if (!$cellValue || $cellValue === '-') {
                        continue;
                    }
                    switch ($columnName) {
                        case OrderModule::COLUMN_TITLE:
                            $fragmentCalculatedData['title'] = $cellValue;
                            break;
                        case OrderModule::COLUMN_WEIGHT:
                            $calculatedData['weight'] += $cellValue;
                            $fragmentCalculatedData['weight'] += $cellValue;
                            break;
                        case OrderModule::COLUMN_AMOUNT:
                            $calculatedData['amount'] += $cellValue;
                            $fragmentCalculatedData['amount'] += $cellValue;
                            break;
                        case OrderModule::COLUMN_MATERIAL:
                            $calculatedData['material'] = $cellValue;
                            $fragmentCalculatedData['material'] = $cellValue;
                            break;
                        case OrderModule::COLUMN_LASER:
                            $calculatedData['laser'] += $cellValue;
                            $fragmentCalculatedData['laser'] += $cellValue;
                            break;
                        case OrderModule::COLUMN_BENDING:
                            $calculatedData['bending'] += $cellValue;
                            $fragmentCalculatedData['bending'] += $cellValue;
                            break;
                    }
                }
            }
            $moduleFragments[] = new BasicOrderModule($fragmentCalculatedData);
        }

        $moduleWelding = $this->getBasicModuleWelding();
        $moduleData = array_merge([
            'title' => $moduleTitle,
            'welding' => $moduleWelding
        ], $calculatedData);
        $newBasicModule = new BasicOrderModule($moduleData);

        $newBasicModule->save();

        foreach ($moduleFragments as $fragment) {
            $fragment->module_id = $newBasicModule->id;
            $fragment->save();
        }

        return true;
    }

    public function getBasicModuleTitle()
    {
        $basicModuleRegexp = '/[0-9]{1,}[\.][0-9]{1,}[\.][0-9]{1,}-[0-9]{1,}[\.]xls/';
        $mainFileToParse = $this->getFileNameByRegExp($basicModuleRegexp);

        if (!$mainFileToParse) {
            throw new BadRequestHttpException(Yii::t(
                'app/models/order',
                'The file with modules does not found'
            ));
        }

        $spreadsheet = IOFactory::load((reset($mainFileToParse))->tempName);
        
        return $spreadsheet
            ->getActiveSheet()
            ->getCellByColumnAndRow(OrderModule::COLUMN_MODULE_NUMBER_INDEX, 1)
            ->getValue();
    }

    public function getBasicModuleWelding()
    {
        $basicModuleWeldingRegexp = '/[0-9]{1,}[\.][0-9]{1,}[\.][0-9]{1,}-[0-9]{1,}\(зварка\)[\.]xls/';
        $mainFileToParse = $this->getFileNameByRegExp($basicModuleWeldingRegexp);

        if (!$mainFileToParse) {
            throw new BadRequestHttpException(Yii::t(
                'app/models/order',
                'The file with welding was not found'
            ));
        }

        $spreadsheet = IOFactory::load((reset($mainFileToParse))->tempName);

        return $spreadsheet
            ->getActiveSheet()
            ->getCellByColumnAndRow(OrderModule::COLUMN_MODULE_NUMBER_INDEX, 2)
            ->getValue();
    }

    public function getParentModule()
    {
        return $this->hasOne(self::className(), ['id' => 'module_id']);
    }
}
