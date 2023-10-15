<?php

use yii\helpers\Url;
use app\models\AuthItem;
use yii\helpers\Html;

$user = Yii::$app->user;
?>
<div class="sidebar sidebar-light sidebar-main sidebar-expand-md align-self-start">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        <span class="font-weight-semibold"><?= Yii::t('app', 'Main menu'); ?></span>
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Main sidebar -->
    <div class="sidebar-content">
        <div class="card card-sidebar-mobile">

            <!-- Header -->
            <div class="card-header header-elements-inline">
                <h6 class="card-title"><?= Yii::t('app', 'Menu'); ?></h6>
                <div class="header-elements">
                    <div class="nav navbar-nav">
                        <div class="nav-item">
                            <a class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block" style="padding: 0">
                                <i class="fa fa-chevron-circle-left font-size-25"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User menu -->
            <div class="sidebar-user">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <div class="media-title font-weight-semibold"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /user menu -->

            <!-- Main navigation -->
            <div class="sidebar-category sidebar-category-visible">
                <div class="card-body p-0">
                    <ul class="nav nav-sidebar" data-nav-type="accordion">

                        <!-- Main -->
                        <li class="nav-item nav-item-submenu">
                            <a href="" class="nav-link">
                                <i class="fas fa-th-list"></i>
                                <span><?= Yii::t('app/models/order', 'Orders'); ?></span>
                            </a>
                            <ul class="nav nav-group-sub" data-submenu-title="<?= Yii::t('app/models/order', 'Orders'); ?>">
                                <li class="nav-item">
                                    <?= Html::a(
                                        '<i class="fa fa-clipboard-list"></i>' . Yii::t('app/models/order', 'List of orders'),
                                        '/order/index',
                                        ['class' => 'nav-link']
                                    ) ?>
                                </li>
                                <li class="nav-item">
                                    <?= Html::a(
                                        '<i class="fa fa-plus"></i>' . Yii::t('app/models/order', 'Create a new order'),
                                        '/order/create',
                                        ['class' => 'nav-link']
                                    ) ?>
                                </li>
                                <li class="nav-item">
                                    <?= Html::a(
                                        '<i class="fa fa-tags"></i>' . Yii::t('app/models/status', 'Statuses'),
                                        '/status/index',
                                        ['class' => 'nav-link']
                                    ) ?>
                                </li>
                                <li class="nav-item">
                                    <?= Html::a(
                                        '<i class="fa fa-clipboard-list"></i>' . Yii::t('app/models/status', 'Status Log'),
                                        '/status-log/index',
                                        ['class' => 'nav-link']
                                    ) ?>
                                </li>
                            </ul>
                        </li>

                        <?php if($user->can('users/manage')): ?>
                            <li class="nav-item nav-item-submenu">
                                <a href="" class="nav-link">
                                    <i class="fas fa-user"></i>
                                    <span><?= Yii::t('app', 'Users'); ?></span>
                                </a>
                                <ul class="nav nav-group-sub" data-submenu-title="<?= Yii::t('app', 'Users'); ?>">
                                    <li class="nav-item">
                                        <a href="<?= Url::base() ?>/client/index" class="nav-link" title="">
                                            <i class="fa fa-th-list"></i><?= Yii::t('app/models/client', 'Clients'); ?>
                                         </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= Url::base() ?>/user/index" class="nav-link" title="">
                                            <i class="fa fa-th-list"></i><?= Yii::t('app/models/user', 'List of all users'); ?>
                                         </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= Url::base() ?>/auth-item/roles" class="nav-link" title="">
                                            <i class="fa fa-th-list"></i><?= Yii::t('app/models/authItem', 'List of all user roles'); ?>
                                         </a>
                                    </li>
                                    <?php if($user->can('user/create')): ?>
                                        <li class="nav-item">
                                            <a href="<?= Url::base() ?>/user/create" class="nav-link" title="">
                                                <i class="fa fa-user-plus"></i><?= Yii::t('app', 'Create a new user'); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if($user->can('auth-item-child/permissions')): ?>
                                        <li class="nav-item">
                                            <a href="<?= Url::base() ?>/auth-item-child/permissions" class="nav-link" title="">
                                                <i class="fa fa-exclamation-triangle"></i><?= Yii::t('app', 'Role permissions management'); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <?php if($user->can(AuthItem::PERMISSION_PRODUCT_MANAGE)): ?>
                            <li class="nav-item nav-item-submenu">
                                <a href="" class="nav-link">
                                    <i class="icon-fold"></i>
                                    <span><?= Yii::t('app', 'Products'); ?></span>
                                </a>
                                <ul class="nav nav-group-sub" data-submenu-title="<?= Yii::t('app', 'Products'); ?>">
                                    <?php if($user->can(AuthItem::PERMISSION_VIEW_PRODUCT_LIST)): ?>
                                        <li class="nav-item">
                                            <a href="<?= Url::base() ?>/product/index" class="nav-link" title="">
                                                <i class="fa fa-th-list"></i><?= Yii::t('app/models/product', 'List of all products'); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if($user->can(AuthItem::PERMISSION_PRODUCT_CREATE)): ?>
                                        <li class="nav-item">
                                            <a href="<?= Url::base() ?>/product/create" class="nav-link" title="">
                                                <i class="fa fa-plus"></i><?= Yii::t('app', 'Create a new product'); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <li class="nav-item">
                                        <a href="<?= Url::base() ?>/product-option/index" class="nav-link" title="">
                                            <i class="fa fa-th-list"></i><?= Yii::t('app/models/productOption', 'List of all product options'); ?>
                                        </a>
                                    </li>

                                    <?php if($user->can(AuthItem::PERMISSION_PRODUCT_OPTION_CREATE)): ?>
                                        <li class="nav-item">
                                            <a href="<?= Url::base() ?>/product-option/create" class="nav-link" title="">
                                                <i class="fa fa-plus-circle"></i><?= Yii::t('app/models/productOption', 'Create a new product option'); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <li class="nav-item">
                                        <a href="<?= Url::base() ?>/basic-order-module/index" class="nav-link" title="">
                                            <i class="fas fa-puzzle-piece"></i><?= Yii::t('app/models/basicOrderModule', 'Typical fragments'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <?php if($user->can(AuthItem::PERMISSION_FILES_SECTION)): ?>
                            <li class="nav-item nav-item-submenu">
                                <a href="" class="nav-link">
                                    <i class="fa fa-file-alt"></i>
                                    <span><?= Yii::t('app', 'Files'); ?></span>
                                </a>
                                <ul class="nav nav-group-sub" data-submenu-title="<?= Yii::t('app/models/fileType', 'File Types') ?>">
                                    <li class="nav-item">
                                        <a href="<?= Url::base() ?>/file-type/index" class="nav-link" title="">
                                            <i class="fa fa-th-list"></i><?= Yii::t('app/models/fileType', 'File Types'); ?>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= Url::base() ?>/file/index" class="nav-link" title="">
                                            <i class="fa fa-th-list"></i><?= Yii::t('app/models/file', 'Files'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item nav-item-submenu">
                            <a href="" class="nav-link">
                                <i class="icon-embed"></i>
                                <span><?= Yii::t('app/models/printedFormFormula', 'Variables / Formulas'); ?></span>
                            </a>
                            <ul class="nav nav-group-sub" data-submenu-title="<?= Yii::t('app/models/printedFormFormula', 'Variables / Formulas'); ?>">
                                <li class="nav-item">
                                    <?= Html::a(
                                        '<i class="icon-embed"></i>' . Yii::t('app/models/printedFormFormula', 'Variables / Formulas'),
                                        '/printed-form-formula/index',
                                        ['class' => 'nav-link']
                                    ) ?>
                                </li>
                                <li class="nav-item">
                                    <?= Html::a(
                                        '<i class="fa fa-file-alt"></i>' . Yii::t('app/models/printedFormTemplate', 'Templates'),
                                        '/printed-form-template/index',
                                        ['class' => 'nav-link']
                                    ) ?>
                                </li>
                                <li class="nav-item">
                                    <?= Html::a(
                                        '<i class="fa fa-layer-group"></i>' . Yii::t('app/models/printedFormGroup', 'Template Groups'),
                                        '/printed-form-group/index',
                                        ['class' => 'nav-link']
                                    ) ?>
                                </li>
                            </ul>
                        </li>
                        <!-- /main -->
                    </ul>
                </div>
            </div>
            <!-- /main navigation -->
        </div>
    </div>
</div>
