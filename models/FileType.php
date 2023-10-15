<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\common\validators\CyrillicValidator;
use app\common\validators\TranslationValidator;
use app\common\validators\AttributeNotChanged;
use yii\data\ActiveDataProvider;
use app\common\traits\SourceMessage;

class FileType extends ActiveRecord
{
    use SourceMessage;

    const SCENARIO_UPDATE = 'update';
    const SCENARIO_CREATE = 'create';

    const PRINTED_TEMPLATE_IMAGE = 'printed_template_image';
    const FILES_UPLOADS_DIR = '@web/uploads/files/';
    const PRINTED_TEMPLATE_IMAGES_DIR = '@web/uploads/printed_formula_images/';
    const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'bmp', 'gif'];

    public $translations;
    public $relations = [
        'title_source_message_id' => 'titleSourceMessage'
    ];
    public $fileAccess;

    public function rules()
    {
        return [
            [['key', 'title_source_message_id', 'entity', 'allowed_extensions', 'fileAccess', 'translations'], 'safe'],
            [
                ['title_source_message_id'], 
                TranslationValidator::className(), 
                'skipOnEmpty' => false
            ],
            [['key', 'entity', 'allowed_extensions'], 'required'],
            [['key'], AttributeNotChanged::className() ,'on' => self::SCENARIO_UPDATE],
            [['key'], 'unique'],
            ['key', CyrillicValidator::className()],
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
            'fileType',
            '\app\models\FileTypeSourceMessage',
            '\app\models\FileTypeMessage'
        );
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
        return $this->hasOne(FileTypeSourceMessage::className(), ['id' => 'title_source_message_id']);
    }

    public function getTitle()
    {
        return Yii::t('fileType', $this->titleSourceMessage->message);
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app/models/fileType', 'Name'),
            'created_at' => Yii::t('app/models/fileType', 'Created At'),
            'title_source_message_id' => Yii::t('app/models/fileType', 'Name'),
            'key' => Yii::t('app/models/fileType', 'Key'),
            'entity' => Yii::t('app/models/fileType', 'Entity'),
            'allowed_extensions' => Yii::t('app/models/fileType', 'Allowed Extensions'),
        ];
    }


    public function getEntities()
    {
        return [
            'Order' => Yii::t('app/models/fileType', 'Order'),
            'Printed template image' => Yii::t('app/models/fileType', 'Printed template image')
        ];
    }

    public function getSelectedEntity()
    {
        return Yii::t('app/models/fileType', $this->entity);
    }

    public static function getAccessActions()
    {
        return [
            'view' => [
                'id' => 1,
                'title' => Yii::t('app', 'View'),
            ],
            'edit' => [
                'id' => 2,
                'title' => Yii::t('app', 'Edit'),
            ],
        ];
    }
}
