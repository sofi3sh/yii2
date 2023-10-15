<?php

namespace app\controllers\api\v1;

use Yii;
use app\controllers\api\v1\BaseController;
use \app\models\OrderModule;

class OrderModuleController extends BaseController
{
    public $modelClass = 'app\models\OrderModule';

    public function actionUpload()
    {
        $requestData = Yii::$app->request->post();
        $orderModule = new OrderModule();
        $orderModule->saveParsedModules($requestData['id']);
        
        Yii::$app->response->statusCode = 200;
        return $this->asJson([
            'success' => true,
            'errors' => []
        ]);
    }

}
