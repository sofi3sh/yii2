<?php

namespace app\models;

use \Yii;
use yii\helpers\Html;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use app\common\traits\SourceMessage;
use app\common\validators\TranslationValidator;
use app\common\validators\CyrillicValidator;

class PrintedFormTemplate extends ActiveRecord
{
    use SourceMessage;

    public $translations;
    public $relations = [
        'title_source_message_id' => 'titleSourceMessage'
    ];

    public function rules()
    {
        return [
            [['title_source_message_id', 'translations', 'convert_to_csv', 'template'], 'safe'],
            [
                ['title_source_message_id'], 
                TranslationValidator::className(), 
                'skipOnEmpty' => false
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'template' => Yii::t('app/models/printedFormTemplate', 'Template'),
            'created_at' => Yii::t('app/models/printedFormFormula', 'Created At'),
            'title_source_message_id' => Yii::t('app', 'Title'),
            'convert_to_csv' => Yii::t('app/models/printedFormTemplate', 'Convert to CSV')
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

    public function getTitle()
    {
        return Yii::t('printedForm', $this->titleSourceMessage->message);
    }

    public function search($params)
    {
        $query = self::find();
        $this->load($params);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function renderTemplate($html, $marks, $data)
    {
        $temporaryMarks = [];
        
        foreach($marks as $mark){
            if(array_key_exists($mark, $temporaryMarks)){
                $html = str_replace($mark, $temporaryMarks[$mark], $html);
                continue;
            }

            $formulaValue = stripslashes($this->getFormulaValue($mark, $data));
            $formulaValue = str_replace("'", '', $formulaValue);
            $html = str_replace($mark, $formulaValue, $html);
            $temporaryMarks[$mark] = $formulaValue;
        }

        return $html;
    }

    public function getFormulaKey($originFormula) 
    {
        $formulaKey = trim($originFormula, '{}');
        $formulaKey = explode('|', $formulaKey)[0];

        return $formulaKey;
    }

    public function getFormulaParams($originFormula) 
    {
        $originFormula = trim($originFormula, '{}');

        return array_slice(explode('|', $originFormula), 1);
    }

    public function isModulesFormula($formula) 
    {
        $modulesNamePattern = '/\bmodule\d{1,}/';
        $formulaKeyword = explode('.', $formula)[0];

        return preg_match($modulesNamePattern, $formulaKeyword) === 1;
    }

    public function getFormulaValueForModules($originFormula, $data) 
    {
        $formulaExpressionKey = trim($originFormula, '{}');
        $formulaExpressionData = explode('_', $formulaExpressionKey);
        $value = '';

        if (!isset($formulaExpressionData[0]) || !isset($formulaExpressionData[1])) {
            return 0;
        }

        $matchedNumbers = [];
        preg_match_all('/\d{1,}/', $formulaExpressionData[0], $matchedNumbers);
        
        if (empty($matchedNumbers)) {
            return 0;
        }


        $moduleNumber = intval($matchedNumbers[0][0]);
        $module = OrderModule::getModuleByNumber($data['modules'], $moduleNumber);

        if (!$module) {
            return 0;
        } 

        if ($formulaExpressionData[1] != 'fragments') {
            $availableFields = array_keys($module->attributes);
            
            return in_array($formulaExpressionData[1], $availableFields) ? 
                $module->{$formulaExpressionData[1]} : 0;
        } 
        
        $fragments = OrderModule::getFragmentsById($data['modules'], $module->id);
        $value = Yii::$app->view->renderFile(
            '@app/views/printed-form-template/orderFragments.php', 
            [
                'fragments' => $fragments
            ]
        );
        
        return !empty($value) ? $value : 0;
    }

    public function getFormulaValue($originFormula, $data)
    {
        $formulaKey = $this->getFormulaKey($originFormula);
        $formulaParams = $this->getFormulaParams($originFormula);
        
        if ($this->isModulesFormula($originFormula)) {
            return $this->getFormulaValueForModules($originFormula, $data);
        }

        $formula = PrintedFormFormula::find()->where(['key' => $formulaKey])->one();

        if (!$formula) {
            return 0;
        }

        $temporaryMarks = [];
        preg_match_all('/{.*?}/i', $formula->expression, $marks);
        $marks = array_unique($marks[0]);

        foreach($marks as $mark){
            if(array_key_exists($mark, $temporaryMarks)){
                $formula->expression = str_replace($mark, $temporaryMarks[$mark], $formula->expression);
                continue;
            }
            $formulaExpressionKey = trim($mark, '{}');
            $formulaExpressionData = explode('.', $formulaExpressionKey);
            $value = $this->getFormulaValue($mark, $data);

            if ($value) {
                $formula->expression = str_replace($mark, $value, $formula->expression);
                $temporaryMarks[$mark] = $value;
                continue; 
            }

            switch(count($formulaExpressionData)){
                case 1:
                    $value = $this->getFormulaValue($mark, $data);
                    $formula->expression = str_replace($mark, $value, $formula->expression);
                    break;
                case 2:
                    if ($formulaExpressionData[0] == 'option') {
                        $value = $this->getFormulaValueForProductOption($formulaExpressionData, $data);
                    } elseif ($formulaExpressionData[0] == 'printed_image') {
                        $imageName = $this->getImageNameByMark($formulaExpressionData);
                        $value = addslashes($this->renderPrintedImage($formulaParams, $imageName));
                    } else {
                        $value = $this->getFormulaValueForOfOrderRelation($formulaExpressionData, $data);
                    }
                    $formula->expression = str_replace($mark, $value, $formula->expression); 
                    break;
                case 3:
                    $value = $this->getFormulaValueForOfOrderRelation($formulaExpressionData, $data);
                    $formula->expression = str_replace($mark, $value, $formula->expression);
                    break;
            }
        }

        error_reporting(0);
        if ($formula->is_system) {
            $value = "'" . $formula->expression . "'";
        } else {
           if (strpos($value, ',') !== false) {
               $formula->expression = "'" . $formula->expression . "'";
           }
           $value = eval('return ' . $formula->expression . ';');
        }

        return empty($value) ? 0 : $value;
    }

    public function renderPrintedImage($formulaParams, $imageName) 
    {
        $imagePath = FileType::PRINTED_TEMPLATE_IMAGES_DIR . $imageName;

        $imageParams = [
            'width' => '400px'
        ];

        if (isset($formulaParams[0]) && is_numeric($formulaParams[0])) {
            $imageParams['width'] = $formulaParams[0] . 'px';
        }

        return Html::img($imagePath, $imageParams);
    }

    public function getFormulaValueForProductOption($formulaExpressionData, $data)
    {
        if (isset($data[$formulaExpressionData[0]][$formulaExpressionData[1]]->title)) {
            return $data[$formulaExpressionData[0]][$formulaExpressionData[1]]->title;
        }
        return !isset($data[$formulaExpressionData[0]][$formulaExpressionData[1]]) 
            ? 0 
            : $data[$formulaExpressionData[0]][$formulaExpressionData[1]]; 
    }
    
    public function getFormulaValueForOfOrderRelation($formulaExpressionData, $data)
    {
        return !isset($data[$formulaExpressionData[0]]->{$formulaExpressionData[1]}) 
            ? 0 
            : $data[$formulaExpressionData[0]]->{$formulaExpressionData[1]};
    }

    public function getImageNameByMark($formulaExpressionData) {
        $imageFile = File::find()->where(['id' => $formulaExpressionData[1]])->one();

        if (!$imageFile) {
            return false;
        }

        return FileType::PRINTED_TEMPLATE_IMAGE 
            . '_' . $formulaExpressionData[1] 
            . '.' . $imageFile->extension;
    }
}
