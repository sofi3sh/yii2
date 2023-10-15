<?php

namespace app\common\helpers\views;

use Yii;
use yii\helpers\Html;
use app\models\FileType;
use app\models\File;

class ImageHelper extends HtmlHelper {
    public static function renderImagePreview($model) {
        $imageFormats = FileType::IMAGE_EXTENSIONS;

        if (in_array(strtolower($model->extension), $imageFormats)) {
            $fileName = $model->getFullFileName();
            $folder = $model->isPrintedTemplateImage() ?
                'printed_formula_images' : 'files';
            return Html::img(
                '@web/uploads/' . $folder . '/' . $fileName,
                ['width' => '100px']
            );
        }

        return Yii::t('app', 'Not available');
    }
}
