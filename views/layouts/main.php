<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <title><?= Yii::t('app', 'Metalpark'); ?></title>

        <?php
        use yii\helpers\Url;
        use yii\helpers\Html;
        use app\common\helpers\views\HtmlHelper;

        $baseUrl = Yii::$app->params['themeUrl'];
        $urlWithLayout = Yii::$app->params['layoutUrl'];
        
        echo
        Html::cssFile('https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900'),
        Html::cssFile($baseUrl.'/global_assets/css/icons/icomoon/styles.min.css'),
        Html::cssFile($baseUrl.'/global_assets/css/icons/fontawesome/styles.min.css'),
        Html::cssFile($urlWithLayout.'/assets/css/bootstrap.min.css'),
        Html::cssFile($urlWithLayout.'/assets/css/bootstrap_limitless.min.css'),
        Html::cssFile($urlWithLayout.'/assets/css/layout.min.css'),
        Html::cssFile($urlWithLayout.'/assets/css/components.min.css'),
        Html::cssFile($urlWithLayout.'/assets/css/colors.min.css'),
        HtmlHelper::css($urlWithLayout.'/assets/css/custom.min.css'),

        HtmlHelper::javaScript($baseUrl.'/global_assets/js/main/jquery.min.js'),
        HtmlHelper::javaScript($baseUrl.'/global_assets/js/main/bootstrap.bundle.min.js'),
        HtmlHelper::javaScript($urlWithLayout.'/assets/js/app.js');

        if (YII_ENV === 'prod') {
            echo HtmlHelper::javaScript($urlWithLayout.'/assets/js/fullstory.js');
        }
        ?>
    </head>

    <body class="navbar-top">
    <?php $this->beginBody() ?>
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
            <?= $this->render('../shared/measurementSystemsList') ?>
            <span class="navbar-text mr-md-3">
                <a href="<?= Url::base() ?>/site/logout" title="<?= Yii::t('app', 'Logout'); ?>" class="badge bg-success-400">
                    <i class="icon-switch2"></i>
                    <?= Yii::t('app', 'Logout'); ?>
                </a>
            </span>
        </div>
    </div>

    <!-- /main navbar -->

    <div class="page-header">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4>
                    <i class="icon-radio-checked position-left"></i>
                    <span class="text-semibold"><?= Yii::t('app', 'Welcome'); ?>, <?= Yii::$app->user->identity->full_name ?>!</span>
                </h4>
            </div>
        </div>
    </div>

    <!-- Page container -->
    <div class="page-content pt-0">

        <!-- Page content -->
        <?= $this->render('./_mainMenu') ?>

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content">
                <?= $content; ?>
            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

        <!-- /page content -->
        
    </div>
    <!-- /page container -->
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
