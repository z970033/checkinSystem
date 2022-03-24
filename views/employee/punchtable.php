<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\TimeCard;
?>
<style type="text/css">
.test {
    color:#4CAF50;
    background-color:red;
}

.Box{
    position: relative;
    display: flex;
    justify-content: flex-start;
    align-items:center;
    margin-bottom: 10px;
}

.colorBox{
    width: 15px; 
    height: 15px;
}
.Boxtext{
    margin-left: 10px; 
    color: black;
    font-weight:bold;
}

</style>

<h1>上班狀況 ( <?=$month?> 月)</h1>

<a style="font-size: 20px" href="<?= Url::to(['employee/punchtable', 'month' => 'last']) ?>"><?= $lastMonth ?> 月</a>
&nbsp;
<a style="font-size: 20px" href="<?= Url::to(['employee/punchtable']) ?>"> <?= $nowMonth ?> 月</a>

<div class="Box">
    <div class="colorBox" style="background-color: #00FF00;"></div>
    <div class="Boxtext"> 遲到和當天忘記打下班卡 </div>
</div>

<div class="Box">
    <div class="colorBox" style="background-color: red;"></div>
    <div class="Boxtext"> 遲到 </div>
</div>

<div class="Box">
    <div class="colorBox" style="background-color: yellow;"></div>
    <div class="Boxtext"> 當天忘記打下班卡 </div>    
</div>


<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'rowOptions' => function ($data) {

        $date = new \DateTime($data->clock_out_at);
        $time = $date->format('H:i:s');

        if ($data->late != '' && $time == TimeCard::AUTO_CHECKOUT_TIME) {
            $color = "#00FF00";
        } else {
            if ($time == TimeCard::AUTO_CHECKOUT_TIME) {
                $color = "yellow";                
            }
            if ($data->late != '') {
                // return ['class' => 'test'];  //background-color會被另外一個更詳盡指定的css蓋掉了
                // return ['style'=>'color:black;background-color:red;'];
                $color = "red";
            }
        }
        
        return ['style'=>'color:black;background-color:'.$color];
    },
    'columns' => [
        // ['class' => 'yii\grid\SerialColumn'],
        [
        	'header' => '日期',
        	'value' => function ($data) {
                return substr($data->clock_in_at, 0, 10);
                // return $data->clock_in_at;
            }
        ],
        [
        	'header' => '上班',
        	'value' => 'clock_in_at',
        ],
        [
        	'header' => '下班',
        	'value' => 'clock_out_at',
        ],
        [
            'format' => 'html',
            'header' => '遲到 (超過09:15)',
            'value' => function ($data) {
                return  $data->late;
            },
        ],         
        // ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
