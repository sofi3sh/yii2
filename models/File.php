<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use app\models\FileType;
use app\models\User;
use app\models\Order;
use app\models\OrderProductOption;
use yii\web\UploadedFile;
use app\common\validators\PrintedImageValidator;

class File extends ActiveRecord
{
    public function rules()
    {
        return [
            [['entity_id'], 'default', 'value' => function() {
                if ($this->isPrintedTemplateImage()) {
                    return 0;
                }
            }],
            [['file_type_id', 'entity_id', 'origin_name'], 'safe'],
            [['file_type_id', 'entity_id', 'origin_name'], 'required'],
            [['extension'], PrintedImageValidator::className()]
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s'),
            ],
        ];   
    }

    public function createPrintedImageFormula() {
        $fileName = $this->getFileName();
        $formula = PrintedFormFormula::find()->where(['key' => $fileName])->one();

        if ($formula) {
            return false;
        }        

        $formula = new PrintedFormFormula();
        $formula->translations = [
            'title_source_message_id' => [
                'en-US' => $this->origin_name,
                'uk' => $this->origin_name
            ]
        ];
        $formula->key = $fileName;
        $formula->expression = '{' . 'printed_image.' . $this->id . '}';
        $formula->is_system = 1;

        return $formula->save() ? $formula : false;
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->user_id = Yii::$app->user->identity->id;
            return true;
        }

        return false;
    }

    public function afterSave($insert, $changedAttributes) {

        if ($insert && $this->isPrintedTemplateImage()) {
            $printedImageFormula = $this->createPrintedImageFormula();

            if (!$printedImageFormula) {
                $this->addError(
                    'file_type_id',
                    Yii::t(
                        'app/models/file',
                        'An error occured while saving the formula. Please, try again later' 
                    )
                );
                return false;
            }

            $this->entity_id = $printedImageFormula->id;
            $this->save();
            return true;
        }
    }

    public function search($params)
    {
        $query = self::find()->with('imageMark');
        $this->load($params);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function attributeLabels()
    {
        return [
            'entity_id' => Yii::t('app/models/file', 'Entity'),
            'full_origin_name' => Yii::t('app/models/file', 'File Name'),
            'size' => Yii::t('app/models/file', 'Size'),
            'entity_id' => Yii::t('app/models/file', 'Entity ID'),
            'created_at' => Yii::t('app/models/fileType', 'Created At'),
            'file_type_id' => Yii::t('app/models/file', 'File Type'),
            'origin_name' => Yii::t('app/models/file', 'File'),
        ];
    }

    public function isPrintedTemplateImage() {
        return $this->fileType->key === FileType::PRINTED_TEMPLATE_IMAGE;
    }

    public function getFileType()
    {
        return $this->hasOne(FileType::className(), ['id' => 'file_type_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getOrderProductOption()
    {
        return $this->hasOne(OrderProductOption::className(), ['product_option_value' => 'id']);
    }

    public function getProductOption()
    {
        return $this->hasOne(ProductOption::className(), ['id' => 'product_option_id'])->via('orderProductOption');
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'entity_id']);
    }

    public function getFormulaKey()
    {
        return 'printed_template_image_' . $this->id;
    }

    public function getImageMark()
    {
        return $this->hasOne(PrintedFormFormula::className(), ['key' => 'formulaKey']);
    }

    public function getFileFolder()
    {
        $targetPath = 'files';

        if ($this->isPrintedTemplateImage()) {
            $targetPath = 'printed_templates';
        }

        return Yii::$app->params['fileFolders'][$targetPath];
    }

    public function saveFileToServer($filePath){
        if(empty($this->origin_name)){
            return false;
        }
        $fileFolder = $this->getFileFolder();
        if (!file_exists($fileFolder)) {
            mkdir($fileFolder, 0777);
        }
        $file = $this->getFilePath();
        $this->origin_name->saveAs($file);
        try {
        $this->size = @filesize($file);
        } catch (Exception $error) {
            $this->size = 0;
        }
        $this->origin_name = $filePath['filename'];
        $this->save();
    }

    public function getFilePath(){
        return $this->getFileFolder() 
            . DIRECTORY_SEPARATOR 
            . $this->getFullFileName(); 
    }

    public function getFileName() {
        return $this->fileType->key . '_' . $this->id;
    }

    public function getFullFileName() {
        return $this->getFileName() . '.' . $this->extension;
    }

    public function getUploadedFile($fileKey)
    {
        if ($fileKey) {
            return $uploadedFile = UploadedFile::getInstanceByName($fileKey);
        }
        return UploadedFile::getInstance($this, 'origin_name');
    }

    public function saveFileRecord($fileKey = null)
    {
        $uploadedFile = $this->getUploadedFile($fileKey);
        $this->origin_name = $uploadedFile;
        $fileInfo = pathinfo($this->origin_name);
        $this->extension = isset($fileInfo['extension']) ? $fileInfo['extension'] : '';
        $this->full_origin_name = $fileInfo['basename'];
        $this->save();

        if ($this->hasErrors()) {
            return false;
        }
        $this->saveFileToServer($fileInfo);
        return true;
    }

    public function updateFileRecord($fileKey = null)
    {
        $oldFilePath = $this->getFilePath();
        $uploadedFile = $this->getUploadedFile($fileKey);
        $this->origin_name = $uploadedFile;
        if (empty($this->origin_name)) {
            unset($this->origin_name);
            return true;
        } 
        $fileInfo = pathinfo($this->origin_name);
        $this->extension = isset($fileInfo['extension']) ? $fileInfo['extension'] : '';
        $this->full_origin_name = $fileInfo['basename'];
        if ($this->save()) {
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
            $this->saveFileToServer($fileInfo);
        }
        return true;
    }

    public static function convertBytesToMB($size, $precision = 2)
    { 
        return bcdiv($size, 1048576, $precision);
    } 
}
