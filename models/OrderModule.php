<?php

namespace app\models;

use app\common\traits\OrderModules;
use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\UploadedFile;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use yii\web\BadRequestHttpException;

class OrderModule extends ActiveRecord
{
    use OrderModules;

    const ROW_HEADER_INDEX = 1;
    const ROW_WELDING_INDEX = 2;

    const COLUMN_MODULE_NUMBER_INDEX = 1;
    const COLUMN_TITLE_INDEX = 2;
    const COLUMN_AMOUNT_INDEX = 4;
    const COLUMN_WELDING_INDEX = 1;
    const COLUMN_WEIGHT_INDEX = 3;

    const COLUMN_AMOUNT = 'D';
    const COLUMN_WEIGHT = 'C';
    const COLUMN_MATERIAL = 'E';
    const COLUMN_LASER = 'J';
    const COLUMN_BENDING = 'K';
    const COLUMN_TITLE = 'B';
    const COLUMN_MODULE_NUMBER = 'A';

    public $moduleComponents = [];
    public $existingModules = [];
    public $existingFragments = [];

    public static function getModuleByNumber($modules, $number)
    {
        foreach ($modules as $module) {
            if ($module['module_number'] === $number) {
                return $module;
            }
        }

        return [];
    }

    public static function getFragmentsById($modules, $id)
    {
        return array_filter($modules, function ($module) use ($id) {
            return $module['module_id'] === $id;
        });
    }

    public function rules()
    {
        return [
            [[
                'order_id',
                'module_number',
                'title',
                'amount',
                'weight',
                'material',
                'laser',
                'bending',
                'welding',
                'moduleComponents',
                'module_id'
            ], 'safe'],
            [['order_id', 'title'], 'required'],
            [['amount', 'weight', 'laser', 'welding'], 'number'],
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

    public function getFragments()
    {
        return $this->hasMany(self::className(), ['module_id' => 'id']);
    }

    public function saveParsedModules($requestOrderId)
    {
        $parsedModuleData = $this->getModuleParsedStaticData();
        $allModuleValues = [];
        foreach ($parsedModuleData as $moduleIndex => $moduleData) {
            $order = Order::findOne($moduleData['order_id']);
            if (!$order || $requestOrderId != $moduleData['order_id']) {
                throw new BadRequestHttpException(Yii::t(
                    'app/models/order',
                    'The order with ID {id} does not exist or you selected incorrect order',
                    [
                        'id' => $moduleData['order_id'],
                    ]
                ));
            }
            $this->moduleComponents = $moduleData['components'];
            unset($moduleData['components']);
            $calculatedData = $this->calculateModuleAttributes();
            $existingOrderModules = $this->loadExistingModules($moduleData['order_id']);

            $moduleNumber = $moduleData['module_number'];
            if (isset($existingOrderModules[$moduleNumber])) {
                $existingOrderModules[$moduleNumber]->delete();
            }
            $newModule = new self(
                array_merge($moduleData, $calculatedData)
            );

            $newModule->save();

            $basicModuleNamePattern = '/[0-9]{1,}[\.][0-9]{1,}[\.][0-9]{1,}-[0-9]{1,}/';
            $matches = [];
            preg_match($basicModuleNamePattern, $moduleData['title'], $matches);

            if (empty($matches)) {
                $this->saveModuleFragment($newModule->id, $newModule->order_id);
                continue;
            }

            $parentModule = BasicOrderModule::find()
                ->where(['title' => $matches[0]])
                ->one();

            if (!$parentModule) {
                $this->saveModuleFragment($newModule->id, $newModule->order_id);
                continue;
            }

            $this->insertBasicModuleFragments($parentModule, $moduleData, $newModule);
        }
        return true;
    }

    public function insertBasicModuleFragments($parentModule, $moduleData, $newModule)
    {
        $basicFragmentsToInsert = BasicOrderModule::find()
            ->where(['module_id' => $parentModule->id])
            ->asArray()
            ->all();

        $existingOrderModule = OrderModule::find()
            ->where([
                'order_id' => $moduleData['order_id'],
                'title' => $moduleData['title'],
                'module_number' => $moduleData['module_number']
            ])
            ->andWhere(['not', ['id' => $newModule->id]])
            ->one();

        if($existingOrderModule) {
            OrderModule::deleteAll([
                'module_id' => $existingOrderModule->id
            ]);

            $existingOrderModule->delete();
        }

        foreach ($basicFragmentsToInsert as $fragment) {
            unset($fragment['id']);
            $fragment['order_id'] = $moduleData['order_id'];
            $fragment['module_number'] = $moduleData['module_number'];
            $fragment['module_id'] = $newModule->id;
            $newFragment = new self($fragment);
            $newFragment->save();
        }

        $updatedAttributes = [
            'amount' => $parentModule->amount,
            'weight' => $parentModule->weight,
            'material' => $parentModule->material,
            'laser' => $parentModule->laser,
            'bending' => $parentModule->bending,
            'welding' => $parentModule->welding
        ];

        $newModule->updateAttributes($updatedAttributes);
    }

    public function loadExistingModules($orderId)
    {
        if (!$this->existingModules) {
            $this->existingModules = self::find()
                ->where(['order_id' => $orderId])
                ->indexBy('module_number')
                ->all();
        }

        return $this->existingModules;
    }

    public function loadExistingFragments($orderId)
    {
        if (!$this->existingFragments) {
            $this->existingFragments = self::find()
                ->where(['order_id' => $orderId])
                ->andWhere(['IS NOT', 'module_id', null])
                ->all();
        }

        return $this->existingFragments;
    }

    public function calculateModuleAttributes()
    {
        $calculatedData = [
            'weight' => 0,
            'material' => '',
            'laser' => 0,
            'bending' => 0
        ];
        foreach ($this->moduleComponents as $excelRow) {
            $cellIterator = $excelRow->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $componentWeight = 0;
            $componentAmount = 0;
            foreach ($cellIterator as $cellColumnName => $cell) {
                $cellValue = $cell->getValue();
                if (!$cellValue || $cellValue === '-') {
                    continue;
                }
                switch ($cellColumnName) {
                    case self::COLUMN_WEIGHT:
                        $componentWeight = $cellValue;
                        break;
                    case self::COLUMN_AMOUNT:
                        $componentAmount = $cellValue;
                        break;
                    case self::COLUMN_MATERIAL:
                        $calculatedData['material'] = $cellValue;
                        break;
                    case self::COLUMN_LASER:
                        $calculatedData['laser'] += $cellValue;
                        break;
                    case self::COLUMN_BENDING:
                        $calculatedData['bending'] += $cellValue;
                        break;
                }
            }
            $calculatedData['weight'] += $componentWeight * $componentAmount;
        }
        return $calculatedData;
    }

    public function trimTextFromModuleData($text, $trim = '5000.')
    {
        return str_replace($trim, '', $text);
    }

    public function getModuleWelding($file)
    {
        $spreadsheet = IOFactory::load($file->tempName);
        $worksheet = $spreadsheet->getActiveSheet();
        return $worksheet
            ->getCellByColumnAndRow(self::COLUMN_WELDING_INDEX, self::ROW_WELDING_INDEX)
            ->getValue();
    }

    public function saveModuleFragment($moduleId, $orderId)
    {
        $fragmentData = [];
        foreach ($this->moduleComponents as $excelRow) {
            $cellIterator = $excelRow->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $fragmentTitle = '';
            foreach ($cellIterator as $cellColumnName => $cell) {
                $cellValue = $cell->getValue();
                if (!isset($cellValue) || $cellValue === '-') {
                    continue;
                }
                switch ($cellColumnName) {
                    case self::COLUMN_TITLE:
                        $fragmentTitle = $cellValue;
                        $fragmentData[$fragmentTitle]['title'] = $cellValue;
                        break;
                    case self::COLUMN_WEIGHT:
                        $fragmentData[$fragmentTitle]['weight'] = $cellValue;
                        break;
                    case self::COLUMN_AMOUNT:
                        $fragmentData[$fragmentTitle]['amount'] = $cellValue;
                        break;
                    case self::COLUMN_MATERIAL:
                        $fragmentData[$fragmentTitle]['material'] = $cellValue;
                        break;
                    case self::COLUMN_LASER:
                        $fragmentData[$fragmentTitle]['laser'] = $cellValue;
                        break;
                    case self::COLUMN_BENDING:
                        $fragmentData[$fragmentTitle]['bending'] = $cellValue;
                        break;
                }
            }
        }

        $existingOrderFragments = $this->loadExistingFragments($orderId);
        foreach ($fragmentData as $title => $data) {
            array_filter($existingOrderFragments, function ($fragment) use ($orderId, $title) {
                if ($fragment->order_id == $orderId && $fragment->title === $title) {
                    $fragment->delete();
                }
            });
            $fragmentDataExtended = array_merge(
                $data,
                [
                    'order_id' => $orderId,
                    'module_id' => $moduleId
                ]
            );
            $newModule = new self($fragmentDataExtended);
            $newModule->save();
        }
    }
}
