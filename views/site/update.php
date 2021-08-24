<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\Task */

$this->title = 'Экзамен';
?>

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

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>
    <?= DatePicker::widget([
        'model' => $model,
        'attribute' => 'date',
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
    ]); ?>
    <?= $form->field($model, 'day') ?>
    <?= Html::submitButton('Сохранить') ?>
    <?php ActiveForm::end(); ?>
