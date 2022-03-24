<?php

namespace app\controllers;
use yii;
use app\models\Employee;
use app\models\Work;
use app\models\TimeCard;
use app\models\WorkTable;
use yii\helpers\Url;
use yii\web\Controller;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQueryInterface;
use yii\ web\Application;
use yii\filters\AccessControl;



class TestController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['admin','employee-leave'],
                        'roles' => ['leaveEmployee'],                        
                    ],
                    [
                        'allow' => true,
                        'actions' => ['worktable', 'punchtable', 'index', 'timecard', 'connect', 'worktable-post' ,'test'],
                    ],

                ],
            ],
        ];
    }

    public function beforeAction($action) {

        //先檢查有沒有沒打卡的，有的話自動打卡
        TimeCard::automaticCheckout($_SESSION['id']);
        // return true;
        return parent::beforeAction($action);
    }

    public function actionConnect()
    {
        $model = new Employee();
        return $this->render('connect', [
                'model' => $model,
            ]);
    }

    public function actionIndex()
    {
        $session = Yii::$app->session;
        $session->open();
        // $user = $session->get('id');
        $_SESSION['id'] = Yii::$app->user->identity->id;
        $url = Url::to(['test/timecard']);
        $time = date('Y-m-d')."%";
        $workFind = Employee::findCheckin();
        // $workFindArray = $workFind[0];

        if ($workFind == null) { //今天還沒打卡上班
            $butVal = "打卡上班";
            $butInStatus = "";
            $butOutStatus = "disabled";
        } else {
            $butVal = "打卡下班";
            $butInStatus = "disabled";
            $butOutStatus = "";
        }

        if ($workFind["status"] == "2") {
            $clockOutAt = $workFind['clock_out_at'];
            $divSta = 'display:none';
        } else {
            $clockOutAt = "";
            $divSta = 'display';
        }

        if ($_SESSION['id'] != null) {
            return $this->render('index', [
                'id' => $user['id'],
                'url' => $url,              
                'butVal' => $butVal,
                'butInStatus' => $butInStatus,
                'butOutStatus' => $butOutStatus,
                'clockInAt' => $workFind['clock_in_at'],
                'clockOutAt' => $clockOutAt,
                'divSta' => $divSta,
            ]);
        } else {
            echo "<h1>你沒有權限~<a href='index.php?r=site/login'>請先登入!!</a></h1>" ;
        }
        
        
    }

    public function actionTimecard()
    {
        $ajaxStatus = Yii::$app->request->post('Status', '');

        $model = new TimeCard();
        
        date_default_timezone_set("Asia/Taipei");
        $date = date('Y-m-d H:i:s');
        $dateLate = date("H:i:s");
        $id = $_SESSION['id'];
        $status = Employee::findCheckin();   
        $late = Employee::timeLate($dateLate); 
        
        if ($status == null && $ajaxStatus == 0) {
            //未打卡
        	$workStatus = "in";
        } elseif ($status != null && $ajaxStatus == 1) {
            $workStatus = "out";    
        }
        // if ($status == null) {
        // 	//未打卡
        // 	$workStatus = "in";
        // } else {
        // 	$workStatus = "out";
        // }

        switch ($workStatus) {

            case"in":
                $model->employee_id = $id;
                $model->clock_in_at  = $date; 
                $model->late = $late;
                $model->status = TimeCard::WORK_STATUS_IN;
                $model->save();
                echo json_encode(array(                  
                    'clockInAt' => $model->clock_in_at,
                    'workValue' => '1',         
                    ));          
                break;

            case"out":
                $link = Yii::$app->db;
                // $sql = 'update time_card set clock_out_at = 1991 where employee_id = 1 and clock_out_at = 0000-00-00 00:00:00';
                // $sql = "UPDATE time_card SET clock_out_at= :time WHERE employee_id = :id AND clock_out_at = '0000-00-00 00:00:00'";
                $sql = "UPDATE time_card SET clock_out_at= :time , status = :status WHERE employee_id = :id AND clock_out_at = '0000-00-00 00:00:00'";
                $status = TimeCard::WORK_STATUS_OUT;
                $sth = $link->createCommand($sql);
                $sth->bindParam(":time", $date);
                $sth->bindParam(":status", $status);
                $sth->bindParam(":id", $id);
                
                $sth->execute(); 
                $clockOutAt = Employee::findCheckout();
                echo json_encode(array(
                    'workValue' => '2',
                    'clockOutAt' => $clockOutAt,                           
                    'workStatus' => $workStatus,
                ));
                break;
            default:
                $exitText = "
                　　*'``・* 。
                　　　|　　　　 `*。　　　　　　　  |＿ﾊ;
                　　,｡∩　　　　 　*  這樣按也不會  |･д･)
                　+　(´･ω･`)　*｡+    成為魔法少女喔|⊂ﾉ′
                　`*｡ ヽ、　 つ *ﾟ* 　　　　　　　　|-Ｊ
                　　`・+｡*・' ﾟ⊃ +ﾟ
                　　☆　　 ∪~ ｡*ﾟ
                　　　`・+｡*・ ﾟ..
                ";
                echo json_encode(array(
                    'workValue' => '0',
                    'errorText' => $exitText,
                ));
        }
   
        // var_dump($model->getErrors());
        // var_export($model);
        

        // return $this->render('timecard',[
        //         'model' => $model,
        //     ]);
    }

    public function actionPunchtable()
    {
        if (!Yii::$app->user->isGuest) {
            $id = Yii::$app->user->identity->id;
            $model = new TimeCard;
            $countMonth = TimeCard::countMonthDay();
            // $query = TimeCard::findBySql('SELECT * FROM time_card WHERE employee_id ='.$id);
            if (Yii::$app->request->get()) {
                if ($_GET['month'] == 'last') {
                    $query = TimeCard::lastMonthQuery($id, $countMonth);
                    $month = $countMonth['lastMonth'];
                } elseif ($_GET['month'] == null) {
                    $query = TimeCard::nowMonthQuery($id, $countMonth);
                    $month = $countMonth['nowMonth'];
                }
            }

            // if ($_GET['month'] == null) {
            //     $query = TimeCard::nowMonthQuery($id, $countMonth);
            // }

            // var_export($countMonth);
            // $query = TimeCard::find()->where(['employee_id' => $id])->andWhere(['between', 'clock_in_at', "2019-04-01", "2019-04-31"]);      
            
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 31, //每頁顯示條數            
                ],
                'sort' => [
                'defaultOrder' => [           
                    'clock_in_at' => SORT_DESC, //[欄位]設定排序
                    ]
                ],

            ]);

            $nowMonthFirstDay = date('Y-m-01', strtotime(date("Y-m-d")));
            $nowMonthLastDay = date('Y-m-d', strtotime("{$nowMonthFirstDay} +1 month -1 day"));

            return $this->render('punchtable', [
                'dataProvider' => $dataProvider,
                'nowMonth' => $countMonth['nowMonth'],
                'lastMonth' => $countMonth['lastMonth'],
                'month' => $month,
            ]);

         } else {
            $this->redirect(['site/login']);  
         }
        
    }
    
    public function actionWorktable()
    {
        if (!Yii::$app->user->isGuest) {

            $id = Yii::$app->user->identity->id;
            $item = $_POST['WorkTable']['work_item'];
            $finish = $_POST['WorkTable']['work_finish'];
            $day = $_POST['WorkTable']['day'];
            $url = Url::to(['employee/worktable-post']);
            $model = new WorkTable();
            $query = WorkTable::findBySql('SELECT * FROM work_table WHERE employee_id ='.$id.' AND day = "'.$day.'"')->all();
            
            $dateD = date('d');
            $dateM = date('m');
            $dateY = date('Y');
            $number = cal_days_in_month(CAL_GREGORIAN, $dateM, $dateY);

            return $this->render('worktable', [
                'model' => $model,   
                'id' => $id, 
                'dateD' => $dateD,
                'dateM' => $dateM,
                'dateY' => $dateY,
                'number' => $number,
                'url' => $url,
            ]);

        } else {
            $this->redirect(['site/login']);
        }
        
    }

    public function actionAdmin()
    {
        if (!Yii::$app->user->isGuest) {

            //老闆&索爾
            $status = Employee::EMPLOYEE_STATUS_BOSS;            
            // $query = Employee::find()->where(['status' => $status]);
            $query = Employee::find()->where('status != :status', [':status' => $status]);                        
            $url = Url::to(['employee/employee-leave']);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    // 'pageSize' => 7, //每頁顯示條數            
                ],
                'sort' => [
                'defaultOrder' => [           
                    'status' => SORT_DESC, //[欄位]設定排序
                    ]
                ],

            ]);

            return $this->render('admin', [
                'dataProvider' => $dataProvider,
                'url' => $url,
            ]);

        } else {
            $this->redirect(['site/login']);
        }
        
    }

    public function actionEmployeeLeave()
    {
        if (!Yii::$app->user->isGuest) {

            $request = Yii::$app->request;

            if ($request->isPost) {
                $employeeId = $_POST['employeeId'];
                $status = $_POST['status'];
                $employeeM = Employee::find()->where(['id' => $employeeId])->one();                
                $employeeM->status = $status;
                $employeeM->save();

                if (!$employeeM->getErrors()) {
                    echo json_encode([
                        'status' => 'success',
                        'data' => [
                            'message' => '修改成功',
                        ],              
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'error' => [
                            'message' => '修改失敗',
                        ],              
                    ]);
                }
                
            }

        } else {
            $this->redirect(['site/login']);
        }
        
    }

    public function actionWorktablePost()
    {
        $id = Yii::$app->user->identity->id;
        $day = $_POST['day'];
        $item = $_POST['workItem']; 
        $finish = $_POST['workFinish'];  
        $status = $_POST['status'];    
        $model = new WorkTable();
        $query = WorkTable::findBySql('SELECT * FROM work_table WHERE employee_id ='.$id.' AND day = "'.$day.'"')->all();
        if (Yii::$app->request->post()) {
            if ($query == null) {
                $model->employee_id = $id;
                $model->work_item = $item;
                $model->work_finish = $finish;
                $model->day = $day;
                $model->save();
            } else {
                // WorkTable::workupdate_post($id, $day, $item, $finish);
                WorkTable::workUpdate($id, $day, $item, $finish);
                           
            }    
            return $this->asJson([
                    'day' => $day,
                ]);  
        }
        
    }

    public function actionLogin()
    {
        $model = new Employee();
        if (Yii::$app->user->isGuest) {
            $this->redirect(['site/login']);   
        } 
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        // return $this->render('login',[
        //         'model' => $model,
        //     ]);
    }

    public function actionRegister()
    {
        $id = $_POST['Employee']['id'];
        $name = $_POST['Employee']['name'];
        $password = $_POST['Employee']['password'];

        // $time = new DateTime('now');
        // $time = new DateTime('now');
        // $time->format('Y-m-d H:i:s');
        $time = date('Y-m-d H:i:s');
        
        $employee = new Employee;
        $employee->id = $id;
        $employee->name = $name;
        $employee->password = $password;
        $employee->settime = $time;
        $employee->save();
        
        if ($employee->getErrors() == null) {
            // echo "成功";
            $this->redirect('index.php?r=employee/login');
        } else {
            // echo "失敗";
            $this->redirect('index.php?r=employee/connect');
        }

        var_dump($employee->getErrors());
        // return $this->render('register');
    }

    public function actionWork()
    {
        $work = new Work;
        $id = $_POST['id'];
        $name = $_POST['name'];

        $link = Yii::$app->db;

        var_dump($link);
        $sql = "select offwork from work where id = :id";
        $sth = $link->createCommand($sql);
        $sth->bindParam(":id",$id);
        $sth->execute();
        $roy = $sth->queryAll();

        $work->day = date('d');
        $work->id = $id;
        $work->name = $name;
        $work->startwork = date('Y-m-d H:i:s');
        $work->save();

        // echo json_encode(array(
        //     'id' => $id,
        //     'error' => $work->getErrors()
        // ));
    }

    public function actionTest()
    {
        // $employeeM = Employee::find()->where(['id' => '1'])->one();
        // // var_dump($employeeM);
        // $employeeM->status = 1;
        // $employeeM->save();
        // var_dump($employeeM->getErrors());

        // return $this->render('test', [
            
        // ]);
    }
}
