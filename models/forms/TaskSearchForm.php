<?php

namespace app\models;

use yii\base\Model;

class TaskSearchForm extends Model {

    public array $categories;
    public bool $withoutResponses;
    public array $period;
    public bool $isDistant;
    const SEARCH_INTERVAL = ['1 hour', '12 hours', 'day'];

    public function rules(): array
    {
        return [
            [['isDistant', 'withoutResponses'], 'boolean'],
            [['categories', 'period'], 'array'],
            ['categories', 'exist', 'attributeName'=>'id', 'className'=>'Category'],
            ['period', 'in', 'range' => self::SEARCH_INTERVAL],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'categories' => 'Категории',
            'withoutResponses' => 'Без откликов',
            'isDistant' => 'Удаленная работа',
            'period' => 'Период'
        ];
    }

    function getInterval() {
        switch($this->period){
            case self::SEARCH_INTERVAL[0]: '1 HOUR'; break;
            case self::SEARCH_INTERVAL[1]: '12 HOUR'; break;
            case self::SEARCH_INTERVAL[2]: '24 HOUR'; break;
        }
    }

    public function search(){
        $query = Task::find();

        $query->where(['status' => Task::STATUS_NEW]);

        if ($this->categories){
            $query->andWhere(['in', 'category_id', $this->categories]);
        }
        if ($this->withoutResponses) {
            $query->leftJoin('response', 'response.task_id = null');
        }
        if (in_array($this->period, self::SEARCH_INTERVAL)){
            $query->andWhere('date_of_publication <= NOW() - INTERVAL' . $this->interval);
        }
        if ($this->isDistant) {
            $query->andWhere(['lat' => null]);
        }
        $query->orderBy('date_of_publication DESC');

        return $query;
    }
}