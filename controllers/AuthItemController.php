<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use \app\controllers\BaseController;
use \app\models\AuthItem;
use \app\models\RbacSourceMessage;
use \app\models\Status;
use \app\models\RoleStatus;

class AuthItemController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create-role'],
                'rules' => [
                    [
                        'actions' => ['create-role'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionCreateRole()
    {
        $request = Yii::$app->request;
        $authItem = new AuthItem();
        $statuses = Status::find()->all();

        if ($request->isGet) {
            return $this->render('createRole', [
                'model' => $authItem,
                'statuses' => $statuses
            ]);
        }

        $newRole = new AuthItem($request->post('AuthItem'));
        $newRole->scenario = AuthItem::SCENARIO_CREATE_ROLE;
        $newRole->save();

        $roleSourceDescription = $request->post('translations')[Yii::$app->sourceLanguage];
        if (!$newRole->hasErrors() && $roleSourceDescription) {
            $rbacSourceMessage = new RbacSourceMessage([
                'category' => RbacSourceMessage::CATEGORY_DEFAULT,
                'message' => $roleSourceDescription
            ]);
            $rbacSourceMessage->save();
            $rbacSourceMessage->addTranslations($request->post('translations'), '\app\models\RbacMessage');
            $newRole->setAttributes(['rbac_source_message_id' => $rbacSourceMessage->id]);
            $roleSaved = $newRole->save();
        }

        if ($roleSaved) {
            RoleStatus::saveAccesses($newRole, $request->post('AuthItem')['authItemAccess']);
        }

        if (!$newRole->hasErrors()) {
            Yii::$app->session->setFlash('success', Yii::t('app/models/authItem', 'New user role was successfully created'));
            $newRole = new AuthItem;
        }

        return $this->render('createRole', [
            'model' => $newRole,
            'statuses' => $statuses
        ]);
    }

    public function actionRoles()
    {
        $searchModel = new AuthItem();
      
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams, 
            AuthItem::TYPE_ROLE
        );

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionUpdateRole($name)
    {
        $request = Yii::$app->request;
        $statuses = Status::find()->all();
        $authItem = AuthItem::findOne($name);

        if ($request->isGet) {
            return $this->render('update', [
                'model' => $authItem,
                'statuses' => $statuses
            ]);
        }
        $authItem->scenario = AuthItem::SCENARIO_UPDATE_ROLE;
        $authItem->setAttributes($request->post('AuthItem'));
        $roleSaved = $authItem->save();

        $authItem->name = $authItem->getOldAttribute('name');

        if ($roleSaved) {
            RoleStatus::saveAccesses($authItem, $request->post('AuthItem')['authItemAccess']);
        }

        if (!$authItem->hasErrors()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/authItem', 'The user role was successfully updated')
            );
            return $this->redirect('/auth-item/roles');
        }

        return $this->render('update', [
            'model' => $authItem,
            'statuses' => $statuses
        ]);
    }

    public function actionDeleteRole($name)
    {
        $authItem = AuthItem::findOne($name);

        if ($authItem->removeRole()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/authItem', 'The user role was successfully deleted')
            );
        }

        return $this->redirect('/auth-item/roles');
    }
}
