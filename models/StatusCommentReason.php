<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use app\common\traits\SourceMessage;

class StatusCommentReason extends ActiveRecord
{
    use SourceMessage;

    public $translations;
    public $relations = [
        'title_source_message_id' => 'titleSourceMessage'
    ];

    const NOT_POSSIBLE_TECH = 'not_possible_technologically';
    const NOT_POSSIBLE_CONSTRUCTIVE = 'not_possible_constructive';
    const R_D_TASK = 'r_d_task';
    const DOES_NOT_COMPLY_MISSION = 'does_not_comply_mission';

    public function rules()
    {
        return [
            [['status_id', 'reason_key', 'title_source_message_id'], 'safe'],
            [['status_id', 'reason_key'], 'required']
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
            'status/comment',
            '\app\models\StatusSourceMessage',
            '\app\models\StatusMessage'
        );
        return true;
    }

    public function getTitleSourceMessage()
    {
        return $this->hasOne(StatusSourceMessage::className(), ['id' => 'title_source_message_id']);
    }

    public function getTitle()
    {
        return Yii::t('status/comment', $this->titleSourceMessage->message);
    }

}
