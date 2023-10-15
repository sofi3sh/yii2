<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use app\models\RelateClientToUser;

class Client extends ActiveRecord
{
    public function rules()
    {
        return [
            [
                [
                    'full_name',
                    'phone',
                    'email',
                    'address_legal',
                    'address_actual',
                    'contractor_type',
                    'client_code',
                    'customer_code',
                    'contact_person',
                    'responsible_person',
                    'referer_user_id'
                ],
                'safe'
            ],
            ['email', 'email'],
            [['contractor_type', 'referer_user_id'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'full_name' => Yii::t('app/models/client', 'Full Name'),
            'email' => Yii::t('app/models/client', 'Email'),
            'phone' => Yii::t('app/models/client', 'Phone'),
            'address_legal' => Yii::t('app/models/client', 'Legal Address'),
            'address_actual' => Yii::t('app/models/client', 'Actual Address'),
            'contractor_type' => Yii::t('app/models/client', 'Contractor Type'),
            'client_code' => Yii::t('app/models/client', 'Client Code'),
            'customer_code' => Yii::t('app/models/client', 'Customer Code'),
            'contact_person' => Yii::t('app/models/client', 'Contact Person'),
            'responsible_person' => Yii::t('app/models/client', 'Responsible Person'),
            'referer_user_id' => Yii::t('app/models/client', 'User who created the client'),
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

    public function getContractorTypes()
    {

        return [
            1 => Yii::t('app/models/client', 'Individual Entity'),
            2 => Yii::t('app/models/client', 'Legal Entity'),
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

    public function getUsers()
    {
        return $this->hasMany(RelateClientToUser::className(), ['client_id' => 'id']);
    }
}
