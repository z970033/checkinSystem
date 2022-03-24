<?php

namespace app\models;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WorkTable;

/**
 * WorkTableSearch represents the model behind the search form of `app\models\WorkTable`.
 */
class WorkTableSearch extends WorkTable
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id'], 'integer'],
            [['day', 'work_item', 'work_finish'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = WorkTable::find();

        // add conditions that should always apply here

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

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'employee_id' => Yii::$app->user->identity->id,
            'day' => $this->day,
        ]);

        $query->andFilterWhere(['like', 'work_item', $this->work_item])
            ->andFilterWhere(['like', 'work_finish', $this->work_finish]);

        return $dataProvider;
    }
}
