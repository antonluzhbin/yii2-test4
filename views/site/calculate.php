<?php

/* @var $this yii\web\View */
/* @var $items array; */

$this->title = 'План занятий';
?>
<h2>План занятий</h2>

<?php if(Yii::$app->session->hasFlash('DOUBLE_DATE_ERROR')): ?>
    <div class="alert alert-danger">
        Нельзя составить план занятий, т.к. несколько экзаменов идут в один день!
    </div>
<?php endif; ?>
<?php if(Yii::$app->session->hasFlash('DATA_ERROR')): ?>
    <div class="alert alert-danger">
        При таких данных нельзя подготовиться к экзаменам!
    </div>
<?php endif; ?>

<div class="site-index">
    <table class="table table-striped table-bordered">
        <tr><th>Дата</th><th>Предмет</th></tr>
    <? foreach ($items as $item) : ?>
        <tr><td><?= $item['begin'] ?></td><td><?= $item['name'] ?></td></tr>
    <? endforeach; ?>
    </table>
</div>
