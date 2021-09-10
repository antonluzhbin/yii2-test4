<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider; */
/* @var $linkSearch string; */
/* @var $linkInsert string; */
/* @var $date_range string; */
/* @var $startDate string; */
/* @var $endDate string; */
/* @var $name string; */
/* @var $email string; */

use yii\grid\GridView;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;
use yii\bootstrap4\Modal;

$this->title = 'Поиск комнат';
?>
<div style="margin-bottom: 20px; margin-top: 20px;">
    <?
    $form = ActiveForm::begin([
        'id' => 'login-form',
        'action' => $linkSearch,
        'options' => ['class' => 'form-horizontal'],
    ]) ?>
    <?
    echo DateRangePicker::widget([
        'name' => 'date_range',
        'useWithAddon' => true,
        'containerOptions' => [ 'style' => 'width: 300px; display: inline-block; margin-right: 20px;' ],
        'language' => 'ru',
        'hideInput' => true,
        'presetDropdown' => false,
        'value' => $date_range,
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
<?php if(Yii::$app->session->hasFlash('SAVE_ERROR')): ?>
    <div class="alert alert-danger">
        Ошибка сохранения!
    </div>
<?php endif; ?>

<?php if(Yii::$app->session->hasFlash('SAVE_OK')): ?>
    <div class="alert alert-success">
        Данные сохранены!
    </div>
<?php endif; ?>

<?php if(Yii::$app->session->hasFlash('VALIDATE_ERROR')): ?>
<div class="alert alert-danger">
    Не верные данные!
</div>
<?php endif; ?>

<?
    Modal::begin([
        'title' => '<h4>Добавить бронь</h4>',
        'id' => 'booking-modal',
    ]);

    $form = ActiveForm::begin([
        'id' => 'booking-form',
        'action' => $linkInsert,
        'options' => ['class' => 'form-horizontal'],
    ]); ?>

    <div>Имя</div>
    <?= Html::input('text', 'name', $name, [ 'style' => 'display: block; width: 100%; margin-bottom: 20px;' ]) ?>

    <div>Email</div>
    <?= Html::input('email', 'email', $email, [ 'style' => 'display: block; width: 100%; margin-bottom: 20px;' ]) ?>

    <?= Html::input('hidden', 'from_date', '', [ 'id' => 'from_date' ]) ?>
    <?= Html::input('hidden', 'to_date', '', [ 'id' => 'to_date' ]) ?>
    <?= Html::input('hidden', 'category', '', [ 'id' => 'category' ]) ?>

    <?= Html::submitButton('Добавить', [ 'class' => 'btn btn-primary' ]) ?>

    <? ActiveForm::end();

    Modal::end();
    ?>
<div class="site-index">
    <?
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'columns' => [
            [
                'attribute' => 'name',
                'label' => 'Категория',
                'content' => function($data) {
                    return Html::encode($data['name']);
                }
            ],
            [
                'attribute' => 'count',
                'label' => 'Количество свободных комнат',
            ],
            [
                'label' => '',
                'content' => function($data) {
                    return Html::a('бронировать', '#', [ 'onclick' => 'showModal(' . $data['id'] . '); return false;']);
                }
            ],
        ],
    ]);

    ?>
<script>
    function showModal(id) {
        $('#category').val(id);
        $('#from_date').val($("[name=from_date]").val());
        $('#to_date').val($("[name=to_date]").val());
        $('#booking-modal').modal('show');
    }
</script>

</div>
