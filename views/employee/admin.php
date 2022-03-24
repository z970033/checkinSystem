
<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */


use yii\grid\GridView;
use yii\helpers\BaseHtml;
use yii\widgets\ActiveField;
use yii\helpers\Url;

$this->title = '員工管理';
$this->params['breadcrumbs'][] = $this->title;

?>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<script>
$(document).ready(function(){ 

	$('select').on('change', function() {
		var status = $(this).children('option:selected').val();
		var employeeId = $(this).data('id');
	  	// alert('status:'+ status + ', employeeId: ' + employeeId);
	  	$.ajax({
	  	    type: "POST",
	  	    url: "<?= $url ?>",
	  	    data:{
	  	        employeeId:employeeId,
	  			status:status,
	  	    },
	  	    dataType: "JSON",	  	    
	  	    success: function (data) {
	  	        if (data.status == 'success') {                
	  	            alert(data.data['message']);
	  	            // $("#employeeList").load('<?= Url::to(['employee/admin']) ?> #employeeList');	  	                   
	  	            location.reload();
	  	        } else {
	  	            alert(data.error['message']);
	  	        }
	  	    },
	  	    error: function (XMLHttpRequest, textStatus, errorThrown) {
	  	        alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
	  	    },
	  	});
	});
	
});


</script>

<?= GridView::widget([
	'id' => 'employeeList',
    'dataProvider' => $dataProvider,
    'columns' => [
        // ['class' => 'yii\grid\SerialColumn'],
        [
        	'header' => '員工編號',
        	'value' => 'id',
        ],
        [
        	'header' => '員工名稱',
        	'value' => 'name',
        ],
        [
        	'header' => '員工狀態',
        	// 'value' => 'status',
        	'value' => function($data){
        		if ($data->status == 1) {
        			return "在職中";
        		} else {
        			return "已離職";
        		}
        	},
        ],
        [
        	'format' => 'raw',
        	'header' => '設定狀態',        	
        	// 'value' => 'status',
        	'value' => function($data){
        		if ($data->status == 1) {
        			return BaseHtml::dropDownList('employee', '1', ['1'=>'在職','0'=>'離職'], ['id' => 'employee'.$data->id, 'data-id' => $data->id]);
        		} else {
        			return BaseHtml::dropDownList('employee', '0', ['1'=>'在職','0'=>'離職'], ['id' => 'employee'.$data->id, 'data-id' => $data->id]);
        		}
        		
        		
        	},
        ],
        // ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
