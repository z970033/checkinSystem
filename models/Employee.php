<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $birthday
 * @property string $created_at
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    /** 0  離職員工 */
    const EMPLOYEE_STATUS_LEAVE = 0;
    /** 1  在職員工 */
    const EMPLOYEE_STATUS_STAY = 1;
    /** 2  老闆&索爾 */
    const EMPLOYEE_STATUS_BOSS = 2;

    const DEPARTMENT_xxx = 0;

    /** 資訊部門-後端 */
    const DEPARTMENT_IT_BACKEND = 1;
    /** 資訊部門-前端 */
    const DEPARTMENT_IT_FRONTEND = 2;
    /** 美術部門 */
    const DEPARTMENT_ART = 3;

    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['username', 'password', 'name', 'birthday'], 'required'],
            [['username', 'password', 'name'], 'required'],
            [['birthday', 'created_at'], 'safe'],
            [['username'], 'string', 'max' => 30],
            [['password'], 'string', 'max' => 256],
            [['name'], 'string', 'max' => 100],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'name' => 'Name',
            'birthday' => 'Birthday',
            'created_at' => 'Created At',
        ];
    }

    public static function findCheckin()
    {
        date_default_timezone_set("Asia/Taipei");
        $time = date('Y-m-d')."%";
        $test ="2019-3-28%";
        $path = __DIR__ . '/../';     
        file_put_contents($path."runtime/findCheckin.log", 
                "----------------------" . PHP_EOL .
                date('Y-m-d H:i:s') . PHP_EOL .
                "memberID =>" . $_SESSION['id'] . PHP_EOL .
                "time => " . $time . PHP_EOL,
                 FILE_APPEND | LOCK_EX
            );

        $link = Yii::$app->db;
        $sql = "SELECT * FROM time_card WHERE clock_in_at LIKE :time AND employee_id = :id";
        $sth = $link->createCommand($sql);
        $sth->bindParam(":time", $time);
        // $sth->bindParam(":time", $test);
        $sth->bindParam(":id", $_SESSION['id']);
        $sth->execute();
        // $value = $sth->queryAll();
        // $num = $roy[0];
        $value = $sth->queryOne();

        return $value;
    }

    public static function findCheckout()
    {
        $time = date('Y-m-d')."%";
        
        $link = Yii::$app->db;
        $sql = "SELECT * FROM time_card WHERE clock_out_at LIKE :time AND employee_id = :id";
        $sth = $link->createCommand($sql);
        $sth->bindParam(":time", $time);
        $sth->bindParam(":id", $_SESSION['id']);
        $sth->execute();
        // $roy = $sth->queryAll();
        // $num = $roy[0];
        $value = $sth->queryOne();

        return $value['clock_out_at'];
    }

    public static function findWorkday($id,$day)
    {
        $link = Yii::$app->db;
        $time = date("Y-m")."%";
        //$sql = "SELECT * FROM work_table WHERE day LIKE :time AND employee_id = :id";
        $sql = "SELECT * FROM work_table WHERE day = :time AND employee_id = :id";
        $sth = $link->createCommand($sql);
        $sth->bindParam(":time", $day);
        $sth->bindParam(":id", $id);
        $sth->execute();
        // $roy = $sth->queryAll();
        // $num = $roy[0];
        $value = $sth->queryOne();
        
        return $value;
    }

    public static function timeLate($time)
    {
        $late = "09:15:00";
        if ($time > $late){
            $cle = strtotime($time) - strtotime($late);
            $h = floor(($cle%(3600*24))/3600);  //%取余
            $m = floor(($cle%(3600*24))%3600/60);
            $s = floor(($cle%(3600*24))%60);

            return $h."小時".$m."分鐘".$s."秒";
        } else {
            return "";
        }
    }

    public static function checkID($id, $department)
    {
        $link = Yii::$app->db;
        $sql = "SELECT name FROM employee WHERE department = :department AND id = :id";
        $sth = $link->createCommand($sql);
        $sth->bindParam(":department", $department);
        $sth->bindParam(":id", $id);
        $sth->execute();
        // $permission = $sth->queryAll();
        $permission = $sth->queryOne();

        return $permission;
    }

    //取出該員工的名字
    public static function name($id)
    {
        $link = Yii::$app->db;
        $sql = "SELECT name FROM employee WHERE id = :id";
        $sth = $link->createCommand($sql);
        // $sth->bindParam(":id", $id);
        $sth->bindValue(":id", $id);
        $sth->execute();
        $permission = $sth->queryOne();
        
        return $permission['name'];
    }

}
