<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

$this->title = Html::encode(Yii::$app->name);
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<script type="text/javascript">
    // console.log("ready!");
</script>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    // NavBar::begin([
    //     'brandLabel' => Yii::$app->name,
    //     'brandUrl' => Yii::$app->homeUrl,
    //     'options' => [
    //         'class' => 'navbar-inverse navbar-fixed-top',
    //     ],
    // ]);
    // echo Nav::widget([
    //     'options' => ['class' => 'navbar-nav navbar-right'],
    //     'items' => [
    //         ['label' => '首頁', 'url' => ['/site/index']],
    //         ['label' => '關於', 'url' => ['/site/about']],
    //         ['label' => '連絡我們', 'url' => ['/site/contact']],
            
    //         Yii::$app->user->isGuest ? (
    //             ['label' => '登入', 'url' => ['/site/login']]
    //         ) : (
    //             '<li>'
    //             .Html::a('打卡', Url::to(['employee/index']))
    //             .'</li>'
    //             .'<li>'
    //             . Html::beginForm(['/site/logout'], 'post')
    //             . Html::submitButton(
    //                 '登出 (' . Yii::$app->user->identity->username . ')',
    //                 ['class' => 'btn btn-link logout']
    //             )
    //             . Html::endForm()
    //             . '</li>'

    //         )
    //     ],
    // ]);
    // NavBar::end();
    
    NavBar::begin([
        'brandLabel' => $this->title,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => '首頁', 'url' => ['/site/index']],
        // ['label' => '關於', 'url' => ['/site/about']],
        // ['label' => '連絡我們', 'url' => ['/site/contact']],
    ];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '註冊', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => '登入', 'url' => ['/site/login']];
    } else {
        $menuItems[] =
        '<li>'
        .Html::a('工作大綱', Url::to(['employee/worktable']))
        .'</li>'
        .'<li>'
        .Html::a('個人工作日誌', Url::to(['employee/myworktable']))
        .'</li>'
        .'<li>'
        .Html::a('打卡狀況', Url::to(['employee/punchtable']))
        .'</li>'
        .'<li>'
        .Html::a('打卡', Url::to(['employee/index']))
        .'</li>';

        if (\Yii::$app->user->can('lookWork') && \Yii::$app->user->can('lookTimecard')) {   //如果有權限才顯示
            if (\Yii::$app->user->can('leaveEmployee')) {
                $menuItems[] = 
                    '<li>'
                    .Html::a('出勤管理', Url::to(['examine/timecard']))
                    .'</li>'
                    .'<li>'
                    .Html::a('工作日誌管理', Url::to(['examine/worklog']))
                    .'</li>'
                    .'<li>'
                    .Html::a('員工管理', Url::to(['employee/admin']))
                    .'</li>';
            } else {
                $menuItems[] = 
                    '<li>'
                    .Html::a('出勤管理', Url::to(['examine/timecard']))
                    .'</li>'
                    .'<li>'
                    .Html::a('工作日誌管理', Url::to(['examine/worklog']))
                    .'</li>';
            }
                
        } else if (\Yii::$app->user->can('lookTimecard')) {
            $menuItems[] = 
                '<li>'
                .Html::a('出勤管理', Url::to(['examine/timecard']))
                .'</li>';
        } else if (\Yii::$app->user->can('lookWork')) {
            $menuItems[] = 
                '<li>'
                .Html::a('工作日誌管理', Url::to(['examine/worklog']))
                .'</li>';
        }

        $menuItems[] =
        '<li>'
        . Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            '登出 (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>';

    }


    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end(); 
    
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

