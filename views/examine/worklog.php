<?php
/* @var $this yii\web\View */
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="row">
    <div class="col-lg-12">
        <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
            <div class="form-group">
                <?php foreach ($employee as $array):?>
                    <input type="button" value=<?= $array['name'] ?> onclick="location.href='<?= Url::to(['examine/worklog', 'id' => $array['id']]) ?>'" class = "btn btn-primary">
                <?php endforeach?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<b>員工 : <?= $name ?></b>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [

        ['class' => 'yii\grid\SerialColumn'],
        [
        	'header' => '員工編號',
        	'value' => 'employee_id',
        ],
        [
        	'header' => '日期',
        	'value' => 'day',
        ],
        [
            'format' => 'html',
        	'header' => '工作大鋼',
        	'value' => function ($data) {
                // return Html::tag('pre', Html::encode($data->work_item));
               return  "<pre>".$data->work_item."</pre>";
            },
        ],  
        [
            'format' => 'html',
            'header' => '完成工作',
            'value' => function ($data) {
               return  "<pre>".$data->work_finish."</pre>";
            },
        ],    

        // ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
