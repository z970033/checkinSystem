
<style>
    .flex-container {
        display: flex;
        flex-flow: row wrap;
        /* justify-content: space-around; */
        justify-content: flex-start;
    }
    @media only screen and (max-width: 768px) {
        .flex-container {
            justify-content: space-around;
        }
    }
    .flex-container > div {
        margin: 10px 20px;
        text-align: center;
        font-size: 30px;
    }
</style>
<script>
function punch(workStatus) {
    var NowDate = new Date();
    var y = NowDate.getFullYear();
    var m = NowDate.getMonth()+1;
    var d = NowDate.getDate();
    var h = NowDate.getHours();
    var i = NowDate.getMinutes();
    var s = NowDate.getSeconds();　

    var time = y+"-"+m+"-"+d+" "+h+":"+i+":"+s;

    $.ajax({
          
        url:"<?= $url ?>",
        type:"POST",
        dataType:"json",
        data:{
            'Status': workStatus
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText); 
        }, 
        success:function(data){
            if (data.workValue == "1") {
                alert("上班打卡成功");
                document.getElementById('intime').innerHTML = "上班："+data.clockInAt;
                //document.getElementById('but').value = "打卡下班";
                document.getElementById('but_in').disabled = true;
                document.getElementById('but_out').disabled = false;
            } else if (data.workValue == "2") {
                alert("下班打卡成功");
                document.getElementById('outtime').innerHTML = "下班："+data.clockOutAt;
                document.getElementById('div_btn').style.display = 'none';
                document.getElementById('but_in').disabled = false;
                document.getElementById('but_out').disabled = true;
            } else {
                alert(data.errorText);
            }                                
        }   

    })
}

function checktime(workStatus) {
    var NowDate = new Date();
    var y = NowDate.getFullYear();
    var m = NowDate.getMonth()+1;
    var d = NowDate.getDate();
    var h = NowDate.getHours();
    var i = NowDate.getMinutes();
    var s = NowDate.getSeconds();　

    var time = y+"-"+m+"-"+d+" "+h+":"+i+":"+s;
    // var btnValue = document.getElementById('but').value;

    switch (workStatus) {
        case 0:
            punch(workStatus);
            break;
        case 1:
            if (h >= 9) {
                //如果還沒超過17點
                if (h <= 17) {
                    var mymessage = confirm("還沒18點確定要打卡?");
                    if(mymessage == true){
                    punch(workStatus);
                    } 
                } else {
                    punch(workStatus);
                }
            } else {
                alert("請重新整理頁面");
            }
            break;
    }

    //如果現在是要打卡下班
    // if (btnValue == "打卡下班") {
    //     if (h >= 9) {
    //         //如果還沒超過17點
    //         if (h <= 17) {
    //             var mymessage = confirm("還沒18點確定要打卡?");
    //             if(mymessage == true){
    //               punch();
    //             } 
    //         } else {
    //             punch();
    //         }
    //     } else {
    //         alert("請重新整理頁面");
    //     }
    // } else {
    //     punch();
    // }

  // if (h < 17) {
  //   var mymessage=confirm("還沒6點確定要打卡?");
  //   if(mymessage==true){
  //     punch();
  //   } 
  // } else {
  //   punch();
  // }
}


 
function ShowTime(){ 
    var NowDate=new Date();
    var y=NowDate.getFullYear();
    var m=NowDate.getMonth()+1;
    var d=NowDate.getDate();
    var h=NowDate.getHours();
    var i=NowDate.getMinutes();
    var s=NowDate.getSeconds();　
	// document.getElementById('showbox').innerHTML = y+'年'+m+'月'+d+'日'+h+'點'+i+'分'+s+'秒';
    document.getElementById('showbox').innerHTML = NowDate.toLocaleString(); 
    setTimeout('ShowTime()',1000);
   // console.log(NowDate.toLocaleString());
}
</script>

<body onload="ShowTime()">
<h1>現在時間：<div id=showbox></div></h1>
<div>
  <div id="div_btn" class="flex-container" style=<?= $divSta ?> >
    <!-- <input type="button" class="btn btn-primary btn-lg" id = "but" value="<?= $butVal ?>" onclick="checktime()">  -->
    <div>
        <input type="button" class="btn btn-primary btn-lg" id = "but_in" value="打卡上班" <?= $butInStatus ?> onclick="checktime(0)">  
    </div>
    <div>
        <input type="button" class="btn btn-primary btn-lg" id = "but_out" value="打卡下班" <?= $butOutStatus ?> onclick="checktime(1)"> 
    </div>
    <!-- <input type="button" class="btn btn-primary btn-lg" id = "test" value="測試中(請先不要按)" onclick="checktime()"> -->
    <input type="hidden" id="work_status" value=<?= $work_status ?> />
  </div>
</div> 


<h2 id = "intime"> 上班：<?= $clockInAt ?></h2>
<h2 id = "outtime">下班：<?= $clockOutAt ?></h2>

</body>
