<?php
use Yii;
?>
<div class="card">
    <div class="card-header">
        <?= Yii::t('app/models/productOption', 'Product Options') ?>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>
                        <?= Yii::t('app/models/productOption', 'Name') ?></td>
                    </th>
                    <th>
                        <?= Yii::t('app/models/productOption', 'Option Key') ?></td>
                    </th>
                    <th>
                        <?= Yii::t('app/models/productOption', 'Value') ?></td>
                    </th>
                </tr>
            </thead>
            <?php foreach ($model->parentOptions as $option) : ?>
                <tr>
                    <td><i class="fa fa-align-justify mr-2"></i><?= $option->title?></td>
                    <td><?= $option->option_key?></td>
                    <td><?= $option->value?></td>
                </tr>
                <?php foreach ($option->childrenOptions as $childrenOption) : ?>
                    <tr>
                        <td style="padding-left: 50px!important;">
                            <i class="fa fa-chevron-up mr-2"></i>
                            <?= $childrenOption->title?>
                        </td>
                        <td><?= $childrenOption->option_key?></td>
                        <td><?= $childrenOption->value?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </table>
    </div>
</div>
