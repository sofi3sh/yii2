<?php
    use yii\helpers\ArrayHelper;
    use \app\models\ProductOption;

    $optionsData = ArrayHelper::index($model->productOptionsArray, 'option_id');
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
                    <th>
                        <?= Yii::t('app/models/productOption', 'Dynamic') ?></td>
                    </th>
                    <th>
                        <?= Yii::t('app/models/productOption', 'Display after') ?></td>
                    </th>
                </tr>
            </thead>
            <?php foreach ($availableProductOptions as $option) : ?>
            <?php 
                $availablePreviousOptions = ProductOption::getAvailablePreviousOptions($availableProductOptions, $option);
                $optionsMap = ArrayHelper::map($availablePreviousOptions, 'id', 'titleWithOptionKey');
                $optionsMap[NULL] = Yii::t('app', 'Not selected');
            ?>
                <tr>
                    <td>
                        <?= $form->field(
                            $model,
                            'productOptions[' . $option->id . '][is_active]'
                        )
                            ->checkbox([
                                'checked' => isset($optionsData[$option->id]),
                                'label' => $option->title
                            ]);
                        ?>
                    </td>
                    <td><?= $option->option_key ?></td>
                    <td><?= $option->value ?></td>
                    <td>
                        <?= $form->field(
                            $model,
                            'productOptions[' . $option->id . '][is_dynamic]'
                        )
                            ->checkbox([
                                'checked' => isset($optionsData[$option->id]) ?
                                    boolval($optionsData[$option->id]['is_dynamic']) : false,
                                'label' => ''
                            ]);
                        ?>
                    </td>
                    <td>
                    <?= 
                        $form->field(
                            $model,
                            'productOptions[' . $option->id . '][previous_option_id]'
                        )
                            ->dropDownList(
                                $optionsMap,
                                isset($optionsData[$option->id]) ? 
                                    ['options' => [
                                        $optionsData[$option->id]['previous_option_id'] => ['selected' => true]
                                    ]] : 
                                    ['options' => [
                                        null => ['selected' => true]
                                    ]]
                            )
                            ->label('');
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
