<?php
namespace app\controllers;

use Yii;
use app\models\TimeCard;
use app\models\Employee;
use app\models\WorkTable;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\filters\AccessControl;

class ExamineController extends \yii\web\Controller
{
	public function behaviors()
	{
	    return [
	        'access' => [
	            'class' => AccessControl::className(),
	            'rules' => [
	                [
	                    'allow' => true,
	                    'actions' => ['timecard'],
	                    'roles' => ['lookTimecard'],
	                ],
	                [
	                    'allow' => true,
	                    'actions' => ['worklog'],
	                    'roles' => ['lookWork'],
	                ],
	            ],
	        ],
	    ];
	}

    public function actionTimecard()
    {
        $countMonth = TimeCard::countMonthDay();
        $employee = $this->permission();
        $id = Yii::$app->request->get('id', $employee[0]['id']);
        if ($id != null) {
            // $nameObj = Employee::findBySql('SELECT name FROM employee WHERE id ='.$id )->one();
            // $nameObj = Employee::find()->where(['id' => $id])->one();
            // $name = $nameObj->name;

            $name = Employee::name($id);

            if (!$this->permissionId($id)) {
                throw new \yii\web\UnauthorizedHttpException('對不起，您現在還沒獲此操作的權限');
            }
        }

        $query = WorkTable::find()->where(['employee_id' => $id]);
        // $query = TimeCard::find()->where(['employee_id' => $id]);
        
        if (Yii::$app->request->get()) {
            if ($_GET['month'] == 'last') {
                $query = TimeCard::lastMonthQuery($id, $countMonth);
            } elseif ($_GET['month'] == null) {
                $query = TimeCard::nowMonthQuery($id, $countMonth);
            }
        }
        
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

    	return $this->render('timecard', [
    	    'dataProvider' => $dataProvider,
    	    'employee' => $employee,
            // 'models' => $models,  
            // 'url' => $url, 
            'name' => $name, 
            'nowMonth' => $countMonth['nowMonth'],
            'lastMonth' => $countMonth['lastMonth'],
            'id' => $id,            
    	]);


    }

    public function actionWorklog()
    {
        $employee = $this->permission();
        $id = Yii::$app->request->get('id', $employee[0]['id']);
        if ($id != null) {
            // $nameObj = Employee::findBySql('SELECT name FROM employee WHERE id ='.$id )->one();
            // $name = $nameObj->name;
            $name = Employee::name($id);
            if (!$this->permissionId($id)) {
                throw new \yii\web\UnauthorizedHttpException('對不起，您現在還沒獲此操作的權限');
            }
        }

        $query = WorkTable::find()->where(['employee_id' => $id]);  

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 7, //每頁顯示條數            
            ],
            'sort' => [
            'defaultOrder' => [           
                'day' => SORT_DESC, //[欄位]設定排序
                ]
            ],

        ]);
        return $this->render('worklog', [
            'dataProvider' => $dataProvider,
            'employee' => $employee,  
            'name' => $name,
        ]);
    }

    //取出該部門員工
    public function permission()
    {   
        //員工狀態(1=在職)
        $status = Employee::EMPLOYEE_STATUS_STAY;

        if (\Yii::$app->user->can('lookAll')) {
            //如果有管看所有人的權限，就顯示出所有員工
            $employee = Employee::findBySql('SELECT id,name FROM employee WHERE status ='.$status )->all(); 

            return $employee;
        } elseif (\Yii::$app->user->can('lookITDepartmentBackend')) {
            //資訊部後端工程師觀看權限
            $department = Employee::DEPARTMENT_IT_BACKEND;
            $employee = Employee::findBySql('SELECT id,name FROM employee WHERE status ='.$status.' AND department ='.$department )->all();

            return $employee;
        } elseif (\Yii::$app->user->can('lookITDepartmentFrontend')) {
            //資訊部前端工程師觀看權限
            $department = Employee::DEPARTMENT_IT_FRONTEND;
            $employee = Employee::findBySql('SELECT id,name FROM employee WHERE status ='.$status.' AND department ='.$department )->all();

            return $employee;
        } elseif (\Yii::$app->user->can('lookArtDepartment')) {
            //資訊部前端工程師觀看權限
            $department = Employee::DEPARTMENT_ART;
            $employee = Employee::findBySql('SELECT id,name FROM employee WHERE status ='.$status.' AND department ='.$department )->all();

            return $employee;
        } else {
            throw new \yii\web\UnauthorizedHttpException('對不起，您現在還沒獲此操作的權限');
        }
    }

    //驗證id
    public function permissionId($id)
    {
        if (\Yii::$app->user->can('lookAll')) {    
            return ture;
        } elseif (\Yii::$app->user->can('lookITDepartmentBackend')) {
            if ($id != null) {
                $department = Employee::DEPARTMENT_IT_BACKEND;
                // $permission = Employee::findBySql("SELECT name FROM employee WHERE department = ".$department." AND id =".$id )->one();
                $permission = Employee::checkID($id, $department); 
                if ($permission != null) {
                    return ture;
                } else {
                    return false;
                }
            }         
        } elseif (\Yii::$app->user->can('lookITDepartmentFrontend')) {
            if ($id != null) {
                $department = Employee::DEPARTMENT_IT_FRONTEND;
                // $permission = Employee::findBySql('SELECT name FROM employee WHERE department = '.$department.' AND id ='.$id )->one();
                $permission = Employee::checkID($id, $department);
                if ($permission != null) {
                    return ture;
                } else {
                    return false;
                }
            } 
        } elseif (\Yii::$app->user->can('lookArtDepartment')) {
            if ($id != null) {
                $department = Employee::DEPARTMENT_ART;
                // $permission = Employee::findBySql('SELECT name FROM employee WHERE department = '.$department.' AND id ='.$id )->one();
                $permission = Employee::checkID($id, $department);
                if ($permission != null) {
                    return ture;
                } else {
                    return false;
                }
            } 
        }
    }

    
}
