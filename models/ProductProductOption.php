<?php

namespace app\models;

use yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

class ProductProductOption extends ActiveRecord
{
    public function rules()
    {
        return [
            [[
                'product_id',
                'option_id', 
                'is_dynamic', 
                'is_system', 
                'previous_option_id'
            ], 'safe'],
            [[
                'product_id', 
                'option_id', 
                'is_dynamic', 
                'is_system'
            ], 'required'],
            [['previous_option_id'], 'default', 'value'=> null],
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

    public static function saveProductOptionRelations($productOptionsData, $productId) 
    {
        foreach ($productOptionsData as $id => $option) {
            $relationData = [
                'product_id' => $productId,
                'option_id' => $id
            ];

            $existingRelation = self::find()
                ->where($relationData)
                ->one();
            
            if (!$existingRelation && $option['is_active']) {
                unset($option['is_active']);
                $option['is_system'] = 0;
                $newRelation = new self(
                    array_merge($option, $relationData)
                );

                $newRelation->save();
            }

            if ($existingRelation && !$option['is_active']) {
                $existingRelation->delete();
            }

            if ($existingRelation && $option['is_active']) {
                unset($option['is_active']);
                $existingRelation->updateAttributes($option);
            }
        }
    }

    public static function getDynamicOptionsForProduct($productId)
    {
        $productOptionsRelations = self::find()
            ->where([
                'product_id' => $productId
            ])
            ->asArray()
            ->all();

        $productOptionsRelations = ArrayHelper::index($productOptionsRelations, 'option_id');

        $optionIds = ArrayHelper::getColumn($productOptionsRelations, 'option_id');

        $options = ProductOption::find()
            ->with([
                'childrenOptions' => function ($query) {
                    $query->with([
                        'titleSourceMessage' => function ($query) {
                            $query->with([
                                'translations' => function ($query) {
                                    $query->where(['language' => Yii::$app->language]);
                                }
                            ]);
                        },
                    ]);
                }
            ])
            ->with([
                'titleSourceMessage' => function ($query) {
                    $query->with([
                        'translations' => function ($query) {
                            $query->where(['language' => Yii::$app->language]);
                        }
                    ]);
                },
            ])
            ->where([
                'id' => $optionIds
            ])
            ->andWhere(['not', ['option_type' => ProductOption::DROPDOWN_CHILD_OPTION_KEY]])
            ->asArray()
            ->all();

            foreach ($options as $index => $option) {
                $options[$index]['is_dynamic'] = $productOptionsRelations[$option['id']]['is_dynamic'];
                $options[$index]['is_system'] = $productOptionsRelations[$option['id']]['is_system'];
                $options[$index]['previous_option_id'] = $productOptionsRelations[$option['id']]['previous_option_id'];
            }

        return $options;
    }
}
