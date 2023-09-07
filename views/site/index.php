<?php

/** @var yii\web\View $this */

$this->title = 'Docker Lists';
?>

<div class="site-index">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Names</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php foreach ($dockers as $docker) : ?>
                <tr>
                    <td><?= $docker['ID'] ?></td>
                    <td><?= $docker['Names'] ?></td>
                    <td><?= $docker['Status'] ?></td>
                    <td>
                        <?php if ($docker['State'] == 'running') : ?>
                            <a href="<?= Yii::$app->urlManager->createUrl(['site/stop', 'id' => $docker['ID']]) ?>" class="btn btn-sm btn-danger">Stop</a>
                        <?php else : ?>
                            <a href="<?= Yii::$app->urlManager->createUrl(['site/start', 'id' => $docker['ID']]) ?>" class="btn btn-sm btn-success">Start</a>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>