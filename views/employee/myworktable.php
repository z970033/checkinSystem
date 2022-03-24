<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkTableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '個人工作日誌';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
/* button.pagebutton{
    background-color: rgba(0,0,0,0);
    border: none;
    font-size: 25px;
    width: 1em;
    height: 1em;
    margin: 0px 5px;
} */
.pagination > li > button {
    position: relative;
    float: left;
    padding: 6px 12px;
    margin-left: -1px;
    line-height: 1.42857143;
    color: #337ab7;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #ddd;
}
.pagination > li:first-child > button{
    margin-left: 0;
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
}
.pagination > li:last-child > button {
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
}
.pagination > .active > button, .pagination > .active > button:hover, .pagination > .active > button:focus {
    z-index: 3;
    color: #fff;
    cursor: default;
    background-color: #337ab7;
    border-color: #337ab7;
}

em {
    background: #efff94;
}

</style>
<script type="text/javascript">
    window.onload = function () {
        $("#worktable table thead").append('<tr id=search></tr>');
        $("#search").append('<td></td> <td></td> <td></td> <td></td>');
        $.each($('#search td'), function(key, value) {
            // console.log(key + ": " + $(this).attr('class'));
            $(this).html('');
            switch(key) {
                case 1:
                    $(this).append("<input type='text' class='form-control' id='searchday' name='day' maxlength='10' placeholder='依照日期搜尋'>");
                    break;
                case 2:
                    $(this).append("<input type='text' class='form-control' id='searchitem' name='work_item' placeholder='依照工作大綱搜尋'>");
                    break;
                case 3:
                    $(this).append("<input type='text' class='form-control' id='searchfinish' name='work_finish' placeholder='依照完成工作搜尋'>");
                    break;
                default:
                    $(this).html('#');
            }
        });

        $.each($('#search td input'), function(key, value) {
            $(this).on("input propertychange",function(){
                getworktable(1);
            });
        });
        getworktable(1)
    };
    var nowpage = 1; 
    var maxpage = 1;
    var showpagemin = 1;
    var showpagemax = 9;
    function getworktable(getpage){
        if (getpage <= 0){
            getpage = 1;
        } else if(getpage > maxpage) {
            getpage = maxpage;
        }      
        var day = $('#searchday').val();
        var item = $('#searchitem').val();
        var finish = $('#searchfinish').val();
        $.ajax({
            url: "<?= $url ?>",
            type: 'POST',
            data : {
                "day" : day,
                "item" : item,
                "finish" : finish,
                "page" : getpage,
                "<?= \Yii::$app->request->csrfParam; ?>" : "<?= \Yii::$app->request->getCsrfToken();?>"
            },
            success: function(html) {
                // console.log(html);
                $('#tebleContent tr').remove();
                nowpage = getpage;
                var dateArray = JSON.parse(html);
                // var respArray = html.split("▶@◀");
                $('#datasize').text(dateArray['pagecount']);
                var serialNumber = Number(dateArray['dataoffset'])+1;
                var date = dateArray['data'];
                for (var key in date) {
                    let day = new Date(Date.parse(date[key]['day']));
                    let today = ['日', '一', '二', '三', '四', '五', '六'];
                    let week = today[day.getDay()];

                    var td1 = $('<td>').text(serialNumber);
                    serialNumber++;
                    var td2 = $('<td>').text(date[key]['day']+` (${week})`);
                    var pre1 = $('<pre>').text(date[key]['work_item']);
                    var td3 = $('<td>').append(pre1);
                    var pre2 = $('<pre>').text(date[key]['work_finish']);
                    var td4 = $('<td>').append(pre2);
                    var tr = $('<tr>').append(td1,td2,td3,td4);             
                    $('#tebleContent').append(tr);
                }

                $('#dataserial').text((Number(dateArray['dataoffset'])+1)+"~"+(serialNumber-1));

                maxpage = Math.ceil(dateArray['pagecount']/7);
                if (maxpage < 9){
                    showpagemin = 1;
                    showpagemax = maxpage;
                } else if ((getpage - 4) <= 0) {
                    showpagemin = 1;
                    showpagemax = 9;
                } else if ((maxpage - 4) <= getpage) {
                    showpagemin = maxpage -8;
                    showpagemax = maxpage;
                } else {
                    showpagemin = (getpage - 4);
                    showpagemax = (getpage + 4);
                }
                $('#page li').remove();
                $('#page').append('<li class="prev disabled"><button class="pagebutton" style="" onclick = "getworktable(nowpage-1)">◀</button></li>');
                pageli(showpagemin);
                $('#page').append('<li class="next"><button class="pagebutton" style="" onclick = "getworktable(nowpage+1)">▶</button></li>');
                function pageli(i) {
                    if (i <= showpagemax){
                        if (i == nowpage) {
                            $('#page').append('<li class="active"><button class="pagebutton" disabled onclick = "getworktable('+(i)+')">'+(i)+'</button></li>');
                        } else {
                            $('#page').append('<li><button class="pagebutton" onclick = "getworktable('+(i)+')">'+(i)+'</button></li>');
                        }
                        pageli(i+1);  
                    }  
                }

                $("#tebleContent>tr").each(function(index, value) {
                    // 日期
                    if (day != "") {
                        var oldDay = $(this).children().eq(1).text();
                        if (oldDay.indexOf(day) >= 0) {                            
                            var arr = oldDay.split(day);
                            var newDay = "";
                            $.each(arr, function(key, value) {
                                newDay += (key != arr.length-1) ? value + "<em>"+day+"</em>" : value;
                            }); 
                            $(this).children().eq(1).text("");
                            $(this).children().eq(1).append(newDay);
                        }
                    }

                    // 工作大鋼
                    if (item != "") {
                        var oldItem = $(this).children().eq(2).text();
                        if (oldItem.indexOf(item) >= 0) {                            
                            var arr = oldItem.split(item);
                            var newItem = "";
                            $.each(arr, function(key, value) {
                                newItem += (key != arr.length-1) ? value + "<em style=background:#f8abf9>"+item+"</em>" : value;
                            }); 
                            $(this).children().eq(2).text("");
                            $(this).children().eq(2).append("<pre>"+newItem);
                        }
                    }

                    // 完成工作
                    if (finish != "") {
                        var oldFinish = $(this).children().eq(3).text();
                        if (oldFinish.indexOf(finish) >= 0) {                            
                            var arr = oldFinish.split(finish);
                            var newFinish = "";
                            $.each(arr, function(key, value) {
                                newFinish += (key != arr.length-1) ? value + "<em style=background:#bbf9ab>"+finish+"</em>" : value;
                            }); 
                            $(this).children().eq(3).text("");
                            $(this).children().eq(3).append("<pre>"+newFinish);
                        }
                    }
                });


            },
            error: function(xhr, ajaxOptions, thrownError) {
                $("#loading").addClass("hide");
                // showNotice('common', xhr.responseText);
                alert(xhr.status + ":請稍後重試 " + thrownError);
            }
        });
    }
</script>

<div id="test">
    <?= $test ?>
</div>
<div class="work-table-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div>
        <p>Showing <b id="dataserial"></b> of <b id="datasize"></b> items.</p>
    </div>
    <div id = "worktable">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>日期</th>
                    <th>工作大鋼</th>
                    <th>完成工作</th>
                </tr>
            </thead>
            <tbody id = "tebleContent">
                <!-- 放內容 -->
            </tbody>
        </table>
    </div>
    <div> 
        <ul id="page" class="pagination">
            <li>
                <!-- 放分頁 -->
            </li>
        </ul>
    </div>
</div>