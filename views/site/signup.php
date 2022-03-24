<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '註冊';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

     <?php if (Yii::$app->session->hasFlash('duplicateUsername')): ?>

            <div class="alert-danger alert fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                此帳號已被申請，請重新填寫
            </div>
    <?php endif; ?>

    <p>請填入以下內容，來完成註冊:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
				
				<?= $form->field($model, 'name')->textInput(['placeholder' => '真實姓名'])->label('姓名：')?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => '限制30個字'])->label('帳號：') ?>            

                <?= $form->field($model, 'password')->passwordInput(['placeholder' => '限制256個字'])->label('密碼：') ?>
                

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
