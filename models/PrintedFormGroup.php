<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use app\common\traits\SourceMessage;
use app\common\validators\TranslationValidator;

class PrintedFormGroup extends ActiveRecord
{
    use SourceMessage;

    public $translations;
    public $save_templates;
    public $relations = [
        'title_source_message_id' => 'titleSourceMessage'
    ];

    public function rules()
    {
        return [
            [['title_source_message_id', 'translations', 'save_templates'], 'safe'],
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

    public function afterSave($insert, $changedAttributes)
    {
        $this->saveTemplates();
        return parent::beforeSave($insert, $changedAttributes);
    }

    public function getTitleSourceMessage()
    {
        return $this->hasOne(PrintedFormSourceMessage::className(), ['id' => 'title_source_message_id']);
    }

    public function getGroupTemplates()
    {
        return $this->hasMany(PrintedFormGroupTemplate::className(), ['printed_form_group_id' => 'id']);
    }

    public function getTemplates()
    {
        return $this->hasMany(PrintedFormTemplate::className(), ['id' => 'printed_form_template_id'])->via('groupTemplates');
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

    public function saveTemplates()
    {
        PrintedFormGroupTemplate::deleteAll(
            'printed_form_group_id = :printed_form_group_id', 
            ['printed_form_group_id' => $this->id]
        );

        foreach($this->save_templates as $templateId){
            $newGroupTemplate = new PrintedFormGroupTemplate([
                'printed_form_group_id' => $this->id,
                'printed_form_template_id' => (int)$templateId,
            ]);
            $newGroupTemplate->save();
        }
    }
}
