<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<h1>註冊會員</h1>

<?php $form = ActiveForm::begin([
        'id' => 'test-form',
        'action' => 'index.php?r=employee/register',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

<?= $form->field($model, 'id')->input('text')->label('帳號：')?>
<?= $form->field($model, 'name')->input('text')->label('姓名：')?>
<?= $form->field($model, 'password')->input('text password')->label('密碼：')?>

	<div class="form-group">
		<div class="col-lg-offset-1 col-lg-11">
			<?= Html::submitButton('註冊', ['class' => 'btn btn-primary', 'name' => 'signup-button'])?>
		</div>
        
    </div>

<?php ActiveForm::end(); ?>
