<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\common\helpers\views\HtmlHelper;
use app\common\helpers\views\SettingsHelper;
use app\models\RbacSourceMessage;

$baseUrl = Yii::$app->params['themeUrl'];
$urlWithLayout = Yii::$app->params['layoutUrl'];
$activeFormSettings = SettingsHelper::activeFormBasic('perm-form');
$authManager = Yii::$app->authManager;

echo
HtmlHelper::javaScript($baseUrl.'/global_assets/js/plugins/tables/datatables/datatables.min.js'),
HtmlHelper::javaScript($baseUrl.'/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js'),
HtmlHelper::javaScript($urlWithLayout.'/assets/js/commonSettings/dataTableResponsive.js');    

echo $this->render('../shared/successAlert'); 
?>

<div class="card">
    <div class="card-header"><h6 class="card-title"><?= Yii::t('app', 'Permissions') ?></h6></div>
    <div class="card-body">
        <?php $form = ActiveForm::begin($activeFormSettings); ?>
            <div class="row justify-content-center table_modify mb-3">
                <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
                <a class="btn btn-danger" href="<?= Url::current() ?>"><?= Yii::t('app', 'Discard') ?></a>
            </div>
            <table class="table table-hover table-bordered datatable-responsive dataTable mb-3">
                <thead>
                    <tr>
                        <td><?= Yii::t('app', 'Action') ?></td>
                        <?php foreach($roles as $role): ?>
                            <td><?= $role->name ?></td>
                        <?php endforeach; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($permissions as $permission): ?>
                        <tr class="permission_row">
                            <td width="200px">
                                <?= 
                                    Yii::t(
                                        RbacSourceMessage::CATEGORY_DEFAULT, 
                                        $permission->titleSourceMessage ? $permission->titleSourceMessage->message : $permission->name
                                    )
                                ?>
                            </td>
                            <?php foreach ($roles as $role): ?>
                                <td align="center">
                                    <?= Html::checkbox(
                                            'Permissions[' . $role->name . '.' . $permission->name . ']', 
                                            $authManager->hasChild($authManager->getRole($role->name), 
                                            $authManager->getPermission($permission->name))
                                        )
                                    ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="row justify-content-center">
                <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
                <a class="btn btn-danger" href="<?= Url::current() ?>"><?= Yii::t('app', 'Discard') ?></a>
            </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
