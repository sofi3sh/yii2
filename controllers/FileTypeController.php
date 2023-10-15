<?php

namespace app\controllers;

use Yii;
use \app\controllers\BaseController;
use app\models\AuthItem;
use app\models\Status;
use app\models\FileType;
use app\models\FileAccess;

class FileTypeController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new FileType();
      
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionSave()
    {
        $request = Yii::$app->request;
        $newFileType = new FileType();
        $statuses = Status::find()->all();
        $userRoles = AuthItem::getAllRoles();
        $updateFileTypeId = $request->get('id');

        if ($updateFileTypeId) {
            $newFileType = FileType::findOne($updateFileTypeId);
            $newFileType->scenario = FileType::SCENARIO_UPDATE;
        }
        
        if ($request->isGet) {
            return $this->render('save', [
                'model' => $newFileType,
                'statuses' => $statuses,
                'userRoles' => $userRoles,
                'accessActions' => FileType::getAccessActions()
            ]);
        }

        $newFileType->setAttributes($request->post('FileType'));
        $newFileType->save();

        if (!$newFileType->hasErrors()) {
            FileAccess::saveAccesses($newFileType->id, $request->post('FileType')['fileAccess']);
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/fileType', 'The file type was successfully saved')
            );
            return $this->redirect('/file-type/index');
        }

        $newFileType->key = $newFileType->getOldAttribute('key');

        return $this->render('save', [
            'model' => $newFileType,
            'statuses' => $statuses,
            'userRoles' => $userRoles,
            'accessActions' => FileType::getAccessActions()
        ]);
    }

}
