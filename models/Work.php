<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "work".
 *
 * @property string $id
 * @property string $name
 * @property string $startwork
 * @property string $offwork
 */
class Work extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['id', 'name', 'startwork', 'offwork'], 'required'],
            [['id', 'name'], 'required'],
            [['startwork', 'offwork'], 'safe'],
            [['id', 'name'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'startwork' => 'Startwork',
            'offwork' => 'Offwork',
        ];
    }
}
