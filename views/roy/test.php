<html>
<script language="javascript"> 
var startDate = new Date();
var y = startDate.getFullYear();
var m = startDate.getMonth()+1;
var endDate = new Date(y,m,1,00,00);
var spantime = (endDate - startDate)/1000;
function getString(dt){
    return dt.getFullYear() + "年" + (dt.getMonth()+1) + "月" +    dt.getDate() + "日" + dt.getHours() + "时" + dt.getMinutes() + "分";
}
function cal(){
    spantime --;
    var d = Math.floor(spantime / (24 * 3600));
    var h = Math.floor((spantime % (24*3600))/3600);
    var m = Math.floor((spantime % 3600)/(60));
    var s = Math.floor(spantime%60);
    str = d + "天 " + h + "时 " + m + "分 " + s + "秒 ";
    document.getElementById("pad").innerHTML = str;
    if (s == 0) {
    	// alert(123);
    }
}

var test = 0;

function myTimer() {
  test++;
  console.log(test % 2);

  if (test % 2 == 0) {
    document.title = "【請記得打卡】";
  } else {
    document.title = "【　　　】";
  }
  if (test == 10) {
    clearInterval(myVar);
  }
}

var dt = new Date();
console.log(dt.getMonth());
console.log(dt.getFullYear());
dt.setMonth(dt.getMonth()-1);
console.log(dt.toLocaleString());

var myVar = setInterval(myTimer, 1000);

window.onload = function(){
    document.getElementById("start_pad").innerHTML = getString(startDate);
    document.getElementById("end_pad").innerHTML = getString(endDate);
    setInterval(cal, 1000);
    // setInterval(function(a) {
    //   test++;
    //   console.log(test);
    //   if (test == 10) {
    //     clearInterval();
    //   }
    // }, 1000);
    document.title = "請記得打卡..............................";
    console.log(123);
}
</script>  
</head>
<body>
     <?php if (Yii::$app->session->hasFlash('signupSuccess')): ?>

            <div class="alert-danger alert fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                成功
            </div>        
    <?php endif; ?>
开始时间：<span id="start_pad"></span><br>
结束时间：<span id="end_pad"></span><br>
剩余时间：<span id="pad"></span>
</body>
</html>