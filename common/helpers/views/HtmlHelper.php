<?php
namespace app\common\helpers\views;

use Yii;

class HtmlHelper
{
    public static function javaScript($url, $attrs = [])
    {
        $url .= '?ver=' . Yii::$app->params['externalFileVersion'];
        $allAttrs = '';

        if (!empty($attrs)) {
            foreach ($attrs as $key => $value) {
                $allAttrs .= $key . '="' . $value . '" ';
            }
        }
        return '<script type="text/javascript" src="' . $url .'"  ' . $allAttrs . '></script>';
    }

    public static function css($url)
    {
        $url .= '?ver=' . Yii::$app->params['externalFileVersion'];
        return '<link rel="stylesheet" type="text/css" href="' . $url .'">';
    }

    public static function javaScriptWithCommitHash($filePath)
    {
        $filePath .= '-' . Yii::$app->params['lastGitCommitHash'] . '.js';
        return '<script type="text/javascript" src="' . $filePath . '"></script>';
    }
}
