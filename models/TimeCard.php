<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "time_card".
 *
 * @property int $employee_id
 * @property string $clock_in_at
 * @property string $clock_out_at
 * @property int $status
 */
class TimeCard extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    /** 打卡上班 */
    const WORK_STATUS_IN = 1;
    /** 打卡下班 */
    const WORK_STATUS_OUT = 2;

    /** 自動打卡下班時間 */
    const AUTO_CHECKOUT_TIME = "23:59:59";    

    public static function tableName()
    {
        return 'time_card';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id'], 'required'],
            [['employee_id', 'status'], 'integer'],
            [['clock_in_at', 'clock_out_at'], 'date', 'format' => 'yyyy-M-d H:m:s'],
            [['clock_in_at', 'clock_out_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Employee ID',
            'clock_in_at' => 'Clock In At',
            'clock_out_at' => 'Clock Out At',
            'status' => 'Status',
        ];
    }

    public static function countMonthDay()
    {
        $nowMonth = date("m");

        //這個要是天數不一樣，就會取錯月份
        // $lastMonth = date("m", strtotime("-1 month"));
        $lastMonth = date('m',strtotime(date('Y-m-1').'-1 month'));
        $nowMonthFirstDay = date('Y-m-01 00:00:00', strtotime(date('Y-m-d')));
        // $nowMonthLastDay = date('Y-m-d', strtotime("{$nowMonthFirstDay} +1 month -1 day"));
        $nowMonthLastDay = date('Y-m-d 23:59:59', strtotime('last day of this month'));
        $lastMonthFirstDay = date('Y-m-01 00:00:00', strtotime('first day of previous month'));
        // $lastMonthLastDay = date('Y-m-d', strtotime("{$lastMonthFirstDay} +1 month -1 day"));
        $lastMonthLastDay = date('Y-m-d 23:59:59', strtotime('last day of previous month'));

        return [
            'nowMonth' => $nowMonth,
            'lastMonth' => $lastMonth,
            'nowMonthFirstDay' => $nowMonthFirstDay,
            'nowMonthLastDay' => $nowMonthLastDay,
            'lastMonthFirstDay' => $lastMonthFirstDay,
            'lastMonthLastDay' => $lastMonthLastDay,
        ];
    }

    public static function nowMonthQuery($id, $countMonth)
    {
        $query = TimeCard::find()
            ->where(['employee_id' => $id])
            ->andWhere([
                'between', 
                'clock_in_at', 
                $countMonth['nowMonthFirstDay'], 
                $countMonth['nowMonthLastDay']
            ]);

        return $query;
    }

    public static function lastMonthQuery($id, $countMonth)
    {
        $query = TimeCard::find()
            ->where(['employee_id' => $id])
            ->andWhere([
                'between', 
                'clock_in_at', 
                $countMonth['lastMonthFirstDay'], 
                $countMonth['lastMonthLastDay']
            ]);

        return $query;
    }

    //自動打卡下班
    public static function automaticCheckout($id)
    {
        date_default_timezone_set("Asia/Taipei");
        $notCheckout = TimeCard::findOne(['employee_id' => $id, 'clock_out_at' => '0000-00-00 00:00:00']);
        $date = new \DateTime($notCheckout->clock_in_at);
        $notCheckoutTime = $date->format('Y-m-d');
        $nowTime = date('Y-m-d');
        if ($nowTime > $notCheckoutTime) {                        
            $notCheckout->clock_out_at = $notCheckoutTime." ".TimeCard::AUTO_CHECKOUT_TIME;
            $notCheckout->save();
            // if ($notCheckout->hasErrors()) {
            //     var_dump($notCheckout->getErrors());
            //   } else {
            //     echo "成功";
            // }
        }
    }
}
    