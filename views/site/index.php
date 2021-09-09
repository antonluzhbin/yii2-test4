<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider; */
/* @var $linkSearch string; */

use yii\grid\GridView;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;

$this->title = 'Бронь';
?>
<div style="margin-bottom: 20px; margin-top: 20px;">
    <?
    $form = ActiveForm::begin([
        'id' => 'login-form',
        'action' => $linkSearch,
        'options' => ['class' => 'form-horizontal'],
    ]) ?>
    <?
    $startDate = (new DateTime())->format('Y-m-d');
    echo DateRangePicker::widget([
    'name' => 'date_range',
    'useWithAddon' => true,
    'containerOptions' => [ 'style' => 'width: 300px; display: inline-block; margin-right: 20px;' ],
    'language' => 'ru',
    'hideInput' => true,
    'presetDropdown' => false,
    'value' => $startDate . ' - ' . $startDate,
    'startAttribute' => 'from_date',
    'endAttribute' => 'to_date',
    'pluginOptions' => [
        'locale' => [ 'format' => 'YYYY-MM-DD' ],
        'separator' => '-',
        'opens'=>'left'
    ]
    ]);
    ?>

    <?= Html::submitButton('Поиск', [ 'class' => 'btn btn-primary', 'style' => 'display: inline-block;' ]) ?>

    <?php ActiveForm::end() ?>
</div>

<?php if(Yii::$app->session->hasFlash('DELETE_OK')): ?>
    <div class="alert alert-success">
        Бронь удалена!
    </div>
<?php endif; ?>
<div class="site-index">
    <?

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'content' => function($data) {
                    return Html::encode($data->name);
                }
            ],
            'email',
            'date_arrival',
            'date_departure',
            [
                'attribute' => 'category_id',
                'content' => function($data) {
                    return Html::encode($data->category->name);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]);

    ?>

</div>
