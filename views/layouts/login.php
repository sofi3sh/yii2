<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <title><?= Yii::t('app', 'Metalpark'); ?> - <?= Yii::t('app', 'Login'); ?></title>
        
        <?php
        use yii\helpers\Html;
        use app\common\helpers\views\HtmlHelper;

        $baseUrl = Yii::$app->params['themeUrl'];
        $urlWithLayout = $baseUrl . '/layout_3/LTR/default';

        echo
        Html::cssFile('https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900'),
        Html::cssFile($baseUrl.'/global_assets/css/icons/icomoon/styles.min.css'),
        Html::cssFile($baseUrl.'/global_assets/css/icons/fontawesome/styles.min.css'),
        Html::cssFile($urlWithLayout.'/assets/css/bootstrap.min.css'),
        Html::cssFile($urlWithLayout.'/assets/css/bootstrap_limitless.min.css'),
        Html::cssFile($urlWithLayout.'/assets/css/layout.min.css'),
        Html::cssFile($urlWithLayout.'/assets/css/components.min.css'),
        Html::cssFile($urlWithLayout.'/assets/css/colors.min.css'),

        HtmlHelper::javaScript($baseUrl . '/global_assets/js/main/jquery.min.js'),
        HtmlHelper::javaScript($baseUrl.'/global_assets/js/main/bootstrap.bundle.min.js');
        ?>
    </head>

    <body class="navbar-bottom">
        <!-- Login wrapper begins -->
        <div class="page-content">

                <!-- Main navbar -->
                <div class="navbar navbar-expand-md navbar-dark fixed-top">

                    <div class="d-md-none">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
                            <i class="icon-tree5"></i>
                        </button>
                        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
                            <i class="icon-paragraph-justify3"></i>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse text-center" id="navbar-mobile">
                        <?= $this->render('../shared/languageList') ?>
                    </div>
                </div>
                <!-- /main navbar -->

            <div class="content-wrapper">
                <div class="content d-flex justify-content-center align-items-center">
                    <?= $content; ?>
                </div>
            </div>
        </div>
        <!-- Login wrapper ends -->
    </body>
</html>
