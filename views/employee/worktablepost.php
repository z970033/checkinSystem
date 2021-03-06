
<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Employee;

$this->title = '工作大綱';
$this->params['breadcrumbs'][] = $this->title;
?>
<button onclick="test()">test</button>
<div class="work-table">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>請輸入工作內容</p>
	<div class="row">
		<div class="col-md-5" style="text-align:center">
			<h4>工作大鋼</h4>
		</div>

  		<div class="col-md-4" style="text-align:center">
			<h4>完成工作</h4>
  		</div>
  		<div class="col-md-2" style="text-align:center">
  		</div>
	</div>
	<div id = "demo">
	</div>
	<!--建立一個月的表單 -->
	 <?php  for($day=1; $day<=$number; $day++):?>   
	    <?php $form = ActiveForm::begin([
		        'id' => 'workform'.$day,
		        'layout' => 'inline',
		        'fieldConfig' => [
		            // 'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-6\">{error}</div>",
		            'template' => "{label}\n<div class=\"col-lg-10\">{input}</div>\n<div class=\"col-lg-3\">{error}</div>",
		            'labelOptions' => ['class' => 'col-lg-3'],
		        ],
		    ]); ?> 
	    	<!--每天的日期 -->
			<b><?= $date_y."/".$date_m."/".$day?></b>

			<!--將每天的工作內容從資料庫抓出來 -->
			<?php

				$day_0 = str_pad($day,2,"0",STR_PAD_LEFT); //將天數補0
				$value = Employee::findWorkday($id,$date_y."-".$date_m."-".$day_0);
				if ($value['day'] == $date_y."-".$date_m."-".$day_0) {
					$work_item = $value['work_item'];
					$work_finish = $value['work_finish'];
				} else {
					$work_item = " ";
					$work_finish = " ";
				}
			?>
			<!-- 顯示輸入框 -->			
	        <?= $form->field($model, 'work_item')->textarea(['rows'=>'3', 'cols'=>"45", 'id' =>'work_item'.$day, 'value' => $work_item])->label('') ?>
	        <?= $form->field($model, 'work_finish')->textarea(['rows'=>'3', 'cols'=>"45", 'id' =>'work_finish'.$day, 'value' => $work_finish])->label('') ?>	 
			<!-- 顯示按鈕 -->
	        <?php if($day >= $date_d) : ?>
                <div class="form-group">
                    <div class="col-lg-offset-2 ">
                        <?= Html::submitButton('送出', ['class' => 'btn btn-primary', 'name' => 'work_button']) ?>
                    </div>
                </div>
        		<?= $form->field($model, 'day')->hiddenInput(['value' => $date_y."-".$date_m."-".$day])->label('') ?>
	        <?php endif; ?>      
	    <?php ActiveForm::end(); ?> 
	<?php endfor?>	
</div>

<script>
var day = <?php echo $number ?> ;
const $source = document.querySelector('#work_item1');
const $demo = document.querySelector('#demo');

const typeHandler = function(e) {
  $demo.innerHTML = e.target.value;
}

$source.addEventListener('input', typeHandler,false);

function test(){
	$.ajax({
		url:"<?= $url ?>",
		type:"POST",
		dataType:"json",
		data:{
			day:"2",
			work_item:"123",

		},
		error: function(XMLHttpRequest, textStatus, errorThrown){  
		       alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText); 
		    }, 
		success:function(data){
		                    alert("成功");
		                }   
	})
}


</script>