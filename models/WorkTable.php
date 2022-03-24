<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "work_table".
 *
 * @property int $employee_id
 * @property string $day
 * @property string $work_item
 * @property string $work_finish
 */
class WorkTable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work_table';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id', 'day'], 'required'],
            [['employee_id'], 'integer'],
            [['day'], 'safe'],
            [['work_item', 'work_finish'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Employee ID',
            'day' => 'Day',
            'work_item' => 'Work Item',
            'work_finish' => 'Work Finish',
        ];
    }

    public function workUpdate($id, $day, $item, $finish)
    {
        $link = Yii::$app->db;
        
        // if ($item == null && $finish == null) {
            // return null;
        // } else {
            $sql = "UPDATE work_table SET work_finish = :finish , work_item = :item WHERE employee_id = :id AND day = :day";
            $sth = $link->createCommand($sql);
            $sth->bindParam(":finish", $finish);
            $sth->bindParam(":item", $item);
            $sth->bindParam(":id", $id);
            $sth->bindParam(":day", $day);
            $sth->execute();
            return true;
        // }
    }

    public function workUpdatePost($id, $day, $item, $finish)
    {
        $link = Yii::$app->db;
        if ($item == null && $finish == null) {
            return null;
        } else {
            if ($item == null) {                    
                $sql = "UPDATE work_table SET work_finish = :value WHERE employee_id = :id AND day = :day";
                $value = $finish;

            }
            if ($finish == null) {
                $sql = "UPDATE work_table SET work_item = :value WHERE employee_id = :id AND day = :day";
                $value = $item;
            }
            $sth = $link->createCommand($sql);
            $sth->bindParam(":value", $value);
            $sth->bindParam(":id", $id);
            $sth->bindParam(":day", $day);
            $sth->execute();      
            
            // return $sql;
            return true;
        }
    }

}

