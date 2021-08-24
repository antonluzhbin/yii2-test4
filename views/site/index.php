<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider; */
/* @var $linkInsert string; */
/* @var $linkCalculate string */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Список экзаменов';
?>
<div style="margin-bottom: 20px;">
<?
echo Html::a('Добавить', $linkInsert,
        [
        'class' => 'btn btn-success',
        'data-pjax' => 0
        ]
    );

echo Html::a('Рассчитать', $linkCalculate,
    [
        'class' => 'btn btn-success',
        'style' => [ 'margin-left' => '20px' ],
        'data-pjax' => 0
    ]
);
?>
</div>
<?php if(Yii::$app->session->hasFlash('DELETE_OK')): ?>
    <div class="alert alert-success">
        Экзамен удален!
    </div>
<?php endif; ?>
<div class="site-index">
    <?

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'columns' => [
            'id',
            'name',
            'date',
            'day',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]);

    ?>

</div>
