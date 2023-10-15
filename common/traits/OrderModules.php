<?php
namespace app\common\traits;

use Yii;
use app\models\OrderModule;
use yii\web\UploadedFile;
use yii\web\BadRequestHttpException;
use \PhpOffice\PhpSpreadsheet\IOFactory;

trait OrderModules {
    public function getModuleWelding($file)
    {
        $spreadsheet = IOFactory::load($file->tempName);
        $worksheet = $spreadsheet->getActiveSheet();

        return $worksheet
            ->getCellByColumnAndRow(OrderModule::COLUMN_WELDING_INDEX, OrderModule::ROW_WELDING_INDEX)
            ->getValue();
    }

    public function getFileNameByRegExp($regexp)
    {
        $uploadedFiles = UploadedFile::getInstancesByName('files');
        $mainFileToParse = array_filter($uploadedFiles, function ($file) use ($regexp) {
            return preg_match($regexp, $file->name);
        });

        return $mainFileToParse;
    }

    public function getModuleParsedStaticData($isBasicModule = false)
    {
        $uploadedFiles = UploadedFile::getInstancesByName('files');
        $mainFileToParse = $this->getFileNameByRegExp('/[0-9.]+.xls/');

        if (!$mainFileToParse) {
            throw new BadRequestHttpException(Yii::t(
                'app/models/order', 
                'The file with modules does not found'
            ));
        }

        $spreadsheet = IOFactory::load((reset($mainFileToParse))->tempName);
        $worksheet = $spreadsheet->getActiveSheet();
        $moduleRows = [];
        foreach ($worksheet->getRowIterator() as $rowExcelIndex => $row) {
            if ($rowExcelIndex == OrderModule::ROW_HEADER_INDEX) {
                continue;
            }

            $moduleNumber = $worksheet
                ->getCellByColumnAndRow(OrderModule::COLUMN_MODULE_NUMBER_INDEX, $rowExcelIndex)
                ->getValue();

            if ($isBasicModule && $moduleNumber) {
                $moduleRows[$moduleNumber]['components'][] = $row;
                continue;
            }
    
            if (!$moduleNumber) {
                $lastModuleIndex = key(array_slice($moduleRows, -1, 1, true));
                $moduleRows[$lastModuleIndex]['components'][] = $row;
                continue;
            }
            $orderTitle = $worksheet
                ->getCellByColumnAndRow(OrderModule::COLUMN_TITLE_INDEX, $rowExcelIndex)
                ->getValue();
            $trimmedOrderTitle = !$isBasicModule ? $this->trimTextFromModuleData($orderTitle) : $orderTitle;
            $modulesWeldingToParse = array_filter($uploadedFiles, function ($file) use ($trimmedOrderTitle) {
                return preg_match('/' . strtolower($trimmedOrderTitle) . '.+зварка/', strtolower($file->name));
            });
            if (!$modulesWeldingToParse) {
                throw new BadRequestHttpException(Yii::t(
                    'app/models/order',
                    'The file with welding for {moduleTitle} does not found', 
                    [
                        'moduleTitle' => $trimmedOrderTitle,
                    ]
                ));
            }

            $moduleAmount = $worksheet
                ->getCellByColumnAndRow(OrderModule::COLUMN_AMOUNT_INDEX, $rowExcelIndex)
                ->getValue();
            $orderId = $worksheet
                ->getCellByColumnAndRow(OrderModule::COLUMN_MODULE_NUMBER_INDEX, OrderModule::ROW_HEADER_INDEX)
                ->getValue();
            $moduleRows[$rowExcelIndex]['module_number'] = $moduleNumber;
            $moduleRows[$rowExcelIndex]['order_id'] = $this->trimTextFromModuleData($orderId);
            $moduleRows[$rowExcelIndex]['amount'] = $moduleAmount;
            $moduleRows[$rowExcelIndex]['title'] = $trimmedOrderTitle;
            $moduleRows[$rowExcelIndex]['welding'] = $this->getModuleWelding(reset($modulesWeldingToParse));
        }

        return $moduleRows;
    }
}