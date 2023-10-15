<div class="row">
    <div class="col-12">
        <?php if ($fragments) : ?>
            <table class="table table-bordered">
                    <thead>
                        <th><?= Yii::t('app/models/orderModule', 'Title') ?></th>
                        <th><?= Yii::t('app/models/orderModule', 'Weight') ?></th>
                        <th><?= Yii::t('app/models/orderModule', 'Amount') ?></th>
                        <th><?= Yii::t('app/models/orderModule', 'Material') ?></th>
                        <th><?= Yii::t('app/models/orderModule', 'Laser') ?></th>
                        <th><?= Yii::t('app/models/orderModule', 'Belding') ?></th>
                    </thead>
                    <tbody>
                        <?php foreach ($fragments as $fragment) : ?>
                            <tr>
                                <td><?= $fragment->title ?></td>
                                <td><?= $fragment->weight ?></td>
                                <td><?= $fragment->amount ?></td>
                                <td><?= $fragment->material ?></td>
                                <td><?= $fragment->laser ?></td>
                                <td><?= $fragment->bending ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
            </table>
        <?php else : ?>
            <p><?= Yii::t('app/models/orderModule', 'No fragments found') ?></p>
        <?php endif; ?>
    </div>
</div>
