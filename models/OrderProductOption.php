<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use \app\models\ProductOption;
use yii\web\UploadedFile;

class OrderProductOption extends ActiveRecord
{
    public function rules()
    {
        return [
            [['order_id', 'product_option_id', 'product_option_value'], 'safe'],
            [['order_id', 'product_option_id'], 'required'],
            [['order_id', 'product_option_id'], 'integer'],
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
        $convertedValueForMetricSystem = $this->convertLengthFieldToUserMeasurementSystem('mm');
        if ($convertedValueForMetricSystem) {
            $this->product_option_value = $convertedValueForMetricSystem;
        }
        return true;
    }

    public static function saveOrderProductOptions($optionsToSave, $orderId)
    {
        $optionsToSave = is_string($optionsToSave) ? json_decode($optionsToSave, true) : $optionsToSave;
        $optionKeys = array_merge(
            array_keys($optionsToSave), 
            array_values($optionsToSave)
        );
        $productOptions = ProductOption::find()
            ->where([
                'option_key' => $optionKeys
            ])
            ->indexBy('option_key')
            ->all();

        foreach($optionsToSave as $optionKey => $optionValue) {
            if ($productOptions[$optionKey]['option_type'] == array_search ('file', ProductOption::OPTION_TYPES)) {
                continue;
            }
            $orderProductOption = new self([
                'order_id' => $orderId,
                'product_option_id' => $productOptions[$optionKey]['id'],
                'product_option_value' => is_bool($optionValue) ? json_encode($optionValue) : $optionValue,
            ]);
            $orderProductOption->save();
        }
    }

    public static function saveFilesOrderProductOptions($productKey, $orderId)
    {
        $product = Product::find()
            ->where([
                'product_key' => $productKey
            ])
            ->with([
                'options' => function ($query) {
                    $query->where([
                        'option_type' => ProductOption::getOptionTypeByName('file')
                    ]);
                    $query->indexBy('option_key');
                }
            ])
            ->one();
        $fileTypes = FileType::find()->select('id, key')->indexBy('key')->all();
        $existingFiles = File::find()
            ->where(['entity_id' => $orderId])
            ->with('productOption')
            ->indexBy('productOption.option_key')
            ->all();
        foreach($product->options as $option) {
            $fileKey = $option->option_key;
            $uploadedFile = UploadedFile::getInstanceByName($fileKey);
            if (!$uploadedFile || isset($existingFiles[$fileKey])) {
                continue;
            }
            $fileModel = new File([
                'entity_id' => $orderId,
                'file_type_id' => $fileTypes[$fileKey]['id'],
                'origin_name' => $uploadedFile
            ]);
            $fileModel->saveFileRecord($fileKey);
            $orderProductOption = new self([
                'order_id' => $orderId,
                'product_option_id' => $option->id,
                'product_option_value' => $fileModel->id,
            ]);
            $orderProductOption->save();
        }
    }

    public function getProductOption()
    {
        return $this->hasOne(ProductOption::className(), ['id' => 'product_option_id']);
    }

    public function getProductOptionAnswer()
    {
        return $this->hasOne(ProductOption::className(), ['option_key' => 'product_option_value']);
    }

    public function getFile()
    {
        return $this->hasOne(File::className(), ['id' => 'product_option_value'])
            ->via('productOption')
            ->andOnCondition(['productOption.option_type' => ProductOption::getOptionTypeByName('file')]);
    }

    public static function mapProductOptionsWithValues($orderId)
    {
        $orderProductOptionsMapped = [];
        $orderProductOptions = self::find()->where([
            'order_id' => $orderId
        ])->all();

        foreach($orderProductOptions as $orderProductOption) {
            $productOptionAnswer = $orderProductOption->productOptionAnswer ? 
                $orderProductOption->productOptionAnswer : $orderProductOption->product_option_value;
            $convertedFieldValue = $orderProductOption->convertLengthFieldToUserMeasurementSystem();
            if ($convertedFieldValue) {
                $productOptionAnswer = $convertedFieldValue;
            }
            $orderProductOptionsMapped[$orderProductOption->productOption->option_key] = $productOptionAnswer;
        }

        return $orderProductOptionsMapped;
    }

    public static function getFormattedProductOptionValue($orderProductOption)
    {
        $convertedFieldValue = $orderProductOption->convertLengthFieldToUserMeasurementSystem();
        if ($convertedFieldValue) {
            return round($convertedFieldValue, 2);
        }

        return $orderProductOption->productOptionAnswer 
            ? $orderProductOption->productOptionAnswer->title 
            : Yii::t('app', $orderProductOption->product_option_value);
    }

    public function convertLengthFieldToUserMeasurementSystem($convertTo = 'inch')
    {
        if (in_array($this->productOption->option_key, ProductOption::LENGTH_FIELDS_TO_CONVERT)) {
            $convertedValue = MeasurementSystem::convertLength($this->product_option_value, $convertTo);
            return round($convertedValue, 2);
        }
        return false;
    }
}
