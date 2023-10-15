<?php

namespace app\controllers;

use Yii;
use \app\controllers\BaseController;
use app\models\User;
use app\models\File;
use app\models\FileType;
use yii\web\UploadedFile;

class FileController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new File();
      
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $fileTypes = FileType::find()->all();
        $fileModel = new File();

        if ($request->isGet) {
            return $this->render('create', [
                'model' => $fileModel,
                'fileTypes' => $fileTypes
            ]);
        }
        
        $fileModel->setAttributes($request->post('File'));
        if ($fileModel->saveFileRecord()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/file', 'The file was successfully created')
            );
            return $this->redirect('/file/index');
        }

        return $this->render('create', [
            'model' => $fileModel,
            'fileTypes' => $fileTypes
        ]);
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $fileModel = File::findOne($id);
        $fileTypes = FileType::find()->all();

        if ($request->isGet) {
            return $this->render('update', [
                'model' => $fileModel,
                'fileTypes' => $fileTypes,
            ]);
        }
        
        $fileModel->setAttributes($request->post('File'));
        if ($fileModel->updateFileRecord()) {
            Yii::$app->session->setFlash(
                'success', 
                Yii::t('app/models/file', 'The file was successfully updated')
            );
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $fileModel,
            'fileTypes' => $fileTypes
        ]);
    }

    public function actionView($id)
    {
        $file = File::findOne($id);
        if (!User::getFileAccess($file, 'view')) {
            Yii::$app->response->statusCode = 400;
            return $this->asJson([
                'success' => false,
                'errors' => [
                    'access' => Yii::t('app', 'You do not have access to this source')
                ]
            ]);
        }
        return Yii::$app->response->sendFile(
            $file->getFilePath(), 
            $file->full_origin_name, 
            ['inline' => true]
        );
    }
}
