<?php
/* @var $this yii\web\View */

?>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>

function test(){
	$.ajax({
	    type: "POST",
	    url: "http://checkinsystem.longrui-tw.com/index.php?r=employee/employee-leave",
	    data:{
	        employeeId:1,
			status:0,
	    },
	    dataType: "JSON",	  	    
	    success: function (data) {
	        if (data.status == 'success') {                
	            alert(data.data['message']);	            
	        } else {
	            alert(data.error['message']);
	        }
	    },
	    error: function (XMLHttpRequest, textStatus, errorThrown) {
	        alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
	    },
	});

}

</script>

<button onclick="test()">test</button>