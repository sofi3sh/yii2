<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use \app\controllers\BaseController;
use \app\models\AuthItem;
use \app\models\AuthItemChild;

class AuthItemChildController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['permissions'],
                'rules' => [
                    [
                        'actions' => ['permissions'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionPermissions()
	{
        $request = Yii::$app->request;
        if ($request->post('Permissions') && Yii::$app->user->can('auth-item-child/permissions')) {
            AuthItemChild::deleteAll();
            foreach ($request->post('Permissions') as $roleWithPermission => $allow){
                $splitedRolePermission = explode('.', $roleWithPermission);
                if (!isset($splitedRolePermission[0]) || !isset($splitedRolePermission[1])) {
                    continue;
                }
                $role = Yii::$app->authManager->getRole($splitedRolePermission[0]);
                $permission = Yii::$app->authManager->getPermission($splitedRolePermission[1]);
                Yii::$app->authManager->addChild($role, $permission);
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'All changes were saved'));
        }

		return $this->render('permissions',array(
			'roles' =>  AuthItem::getAllRoles(['name', 'rbac_source_message_id']),
			'permissions' => AuthItem::getAllPermissions(['name', 'rbac_source_message_id']),
		));
	}
}
