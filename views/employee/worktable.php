
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
<style type="text/css">
  .fixed {
  position: fixed;
 /* bottom: 3%;
  right: 0;
  width: 55%;
  background-color: white;*/
  left: 46%;
  top: 95%;
  transform: translate(-50%,-50%);
}

</style>
<!-- <button class = "btn btn-primary" onclick="test()">移動到今天</button> -->
<!-- <div id = "save_status" style="background-color:#DEDEDE;opacity:0.7;"></div> -->
<div class="work-table">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>請直接輸入內容，會自動儲存</p>
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
	 <?php  for($day = 1; $day <= $number; $day++):?>
	    <?php $form = ActiveForm::begin([
		        'id' => 'workform'.$day,
		        'layout' => 'inline',
		        'fieldConfig' => [
		            // 'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-6\">{error}</div>",
		            'template' => "{label}\n<div class=\"col-lg-10\">{input}</div>\n<div class=\"col-lg-3\">{error}</div>",
		            'labelOptions' => ['class' => 'col-lg-3'],
		        ],
		    ]); ?>

			<!--將每天的工作內容從資料庫抓出來 -->
			<?php

				$day_0 = str_pad($day,2,"0",STR_PAD_LEFT); //將天數補0
				$value = Employee::findWorkday($id,$dateY."-".$dateM."-".$day_0);
				if ($value['day'] == $dateY."-".$dateM."-".$day_0) {
					$work_item = $value['work_item'];
					$work_finish = $value['work_finish'];
				} else {
					$work_item = "";
					$work_finish = "";
				}

				//取得星期
				$weekday = date('w', strtotime($dateY."/".$dateM."/".$day));
		    	$weeklist = array('日', '一', '二', '三', '四', '五', '六');

		    	$readonly = ($dateD > $day) ? true : false;
			?>

			<!--每天的日期 -->
			<b><?= $dateY."/".$dateM."/".$day_0." (".$weeklist[$weekday].")"?></b>

			<!-- 顯示輸入框 -->	<!-- 利用data-xx來宣告自己的屬性 -->
	        <?= $form->field($model, 'work_item')->textarea(['rows'=>'3', 'cols'=>"45", 'id' =>'work_item'.$day,
	        	'data-day' => $day, 'value' => $work_item, 'readonly' => $readonly])->label('') ?>

	        <?= $form->field($model, 'work_finish')->textarea(['rows'=>'3', 'cols'=>"45", 'id' =>'work_finish'.$day,
	        	'data-day' => $day,'value' => $work_finish, 'readonly' => $readonly])->label('') ?>
			<!-- 顯示按鈕 -->
	        <!-- <?php if($day >= $date_d) : ?>
                <div class="form-group">
                    <div class="col-lg-offset-2 ">
                        <?= Html::submitButton('送出', ['class' => 'btn btn-primary', 'name' => 'work_button']) ?>
                    </div>
                </div>
        		<?= $form->field($model, 'day')->hiddenInput(['value' => $dateY."-".$dateM."-".$day])->label('') ?>
	        <?php endif; ?>  -->

	    <?php ActiveForm::end(); ?>
	<?php endfor?>
</div>

<div class = "fixed" id ="save_status" style="background-color:#DEDEDE;opacity:0.7;"></div>

<script>


var day = <?php echo $number ?> ;
var y = <?php echo $dateY ?> ;
var m = <?php echo $dateM ?> ;
var today = <?php echo $dateD ?> ;

window.onload = function () {
	// if (today == 1) {
	// 	var a = 1;
	// } else {
	// 	var a = today-1;
	// }

	var a = (today == 1)?(today):(today-1);


	//使頁面跳到指定id
	if (today != 1) {
		$('html, body').animate({
            scrollTop: $("#work_item"+a).offset().top
        }, 0);
	}
	$("#work_item"+today).focus();

};


//work_item 監聽事件
for (a = today; a <= day; a++) {
	window['item'+a] = document.querySelector('#work_item'+a);
	window['item'+a].addEventListener('input', function(){
		// alert(this.dataset.day);
		// console.log(this.value);
		document.getElementById('save_status').innerHTML = "儲存中....";
		var workFinish = document.getElementById('work_finish'+this.dataset.day);
		$.ajax({
			url:"<?= $url ?>",
			type:"POST",
			dataType:"json",
			data:{
				day:y+"-"+m+"-"+this.dataset.day,
				workItem:this.value,
				workFinish:workFinish.value,
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
		       alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
		    },

			success:function(data){
		        document.getElementById('save_status').innerHTML = data.day+"儲存完成";
		        setTimeout("document.getElementById('save_status').innerHTML=''", 3000 );
	        }
		})
	}, false);

}

//work_finish監聽事件
for (a = today; a <= day; a++) {
	window['finish'+a] = document.querySelector('#work_finish'+a);
	window['finish'+a].addEventListener('input', function(){
		// alert(this.dataset.day);
		// console.log(this.value);
		document.getElementById('save_status').innerHTML = "儲存中....";
		var workItem = document.getElementById('work_item'+this.dataset.day);
		$.ajax({
			url:"<?= $url ?>",
			type:"POST",
			dataType:"json",
			data:{
				day:y+"-"+m+"-"+this.dataset.day,
				workItem:workItem.value,
				workFinish:this.value,
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
		       	alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
		    },
			success:function(data){
		        document.getElementById('save_status').innerHTML = data.day+"儲存完成";
		        setTimeout("document.getElementById('save_status').innerHTML=''", 3000 );
	        }
		})
	}, false);

}


function test(){
	// $.ajax({
	// 	url:"<?= $url ?>",
	// 	type:"POST",
	// 	dataType:"json",
	// 	data:{
	// 		day:"2",
	// 		work_item:"123",
	// 		work_finish:"",

	// 	},
	// 	error: function(XMLHttpRequest, textStatus, errorThrown){
	// 	       alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
	// 	    },
	// 	success:function(data){
	// 	                    alert(data.day);
	// 	                }
	// })


	//利用dataset.xx來讀取自己宣告的屬性
	// const $source = document.querySelector('#work_item2');
	// alert($source.dataset.day);

	//jquery
	// alert($("#work_item2").data().day);
	// var a = document.getElementById('work_item1');
	// alert(a.dataset.day);

	// window.scrollTo(0,800);
	var a = today-1;
	$('html, body').animate({
	                scrollTop: $("#work_item"+a).offset().top
	            }, 0);

}

</script>
