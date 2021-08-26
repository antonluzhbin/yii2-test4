<?php

/* @var $this yii\web\View */
/* @var $dataProvider DataProvider; */

use yii\debug\models\timeline\DataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'План занятий';
?>
<h2>План занятий</h2>

<?php if(Yii::$app->session->hasFlash('DOUBLE_DATE_ERROR')): ?>
    <div class="alert alert-danger">
        Нельзя составить план занятий, т.к. несколько экзаменов идут в один день!
    </div>
<?php elseif (Yii::$app->session->hasFlash('DATA_ERROR')): ?>
    <div class="alert alert-danger">
        При таких данных нельзя подготовиться к экзаменам!
    </div>
<?php else: ?>

<div class="site-index">
    <?
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'begin',
                [
                    'attribute' => 'name',
                    'content' => function($data) {
                        return Html::encode($data->name);
                    }
                ],
            ],
        ]);
    ?>
</div>

<?php endif; ?>