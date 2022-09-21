<?php

namespace app\models\forms;

use app\models\Category;
use app\models\Task;
use yii\base\Model;

class TaskSearchForm extends Model {

    public $categories;
    public $bonus;
    public bool $withoutResponses = false;
    public bool $isDistant = false;
    public $period;

    const HOUR_1 = '1 час';
    const HOURS_12 = '12 часов';
    const HOURS_24 = '24 часа';
    const ALL_TASKS = 'все';

    const SEARCH_INTERVAL = [self::HOUR_1, self::HOURS_12, self::HOURS_24, self::ALL_TASKS];

    public function rules(): array
    {
        return [
            [['isDistant', 'withoutResponses'], 'boolean'],
            ['period', 'in', 'range' => array_keys(self::SEARCH_INTERVAL)],
            ['categories', 'each', 'rule' => ['exist', 'targetClass' => Category::class, 'targetAttribute' => ['categories' => 'id']]]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'categories' => 'Категории',
            'period' => 'Период',
            'bonus' => 'Дополнительно',
            'withoutResponses' => 'Без откликов',
            'isDistant' => 'Удаленная работа'
        ];
    }

    public function search(): \yii\db\ActiveQuery
    {
        $query = Task::find();
        $query->where(['status' => Task::STATUS_NEW]);

        if ($this->isDistant) {
            $query->andWhere('latitude is null or city_id is null');
        }
        if ($this->withoutResponses) {
            $query->leftJoin('response', 'response.task_id = null');
        }
        if ($this->categories){
            $query->andWhere(['in', 'category_id', $this->categories]);
        }
        if (in_array($this->period, array_keys(self::SEARCH_INTERVAL))){
            switch($this->period){
                case 0: $query->andWhere('date_of_publication >= NOW() - INTERVAL 1 HOUR'); break;
                case 1: $query->andWhere('date_of_publication >= NOW() - INTERVAL 12 HOUR'); break;
                case 2: $query->andWhere('date_of_publication >= NOW() - INTERVAL 24 HOUR'); break;
                case 3: $query->andWhere('date_of_publication <= NOW()'); break;
            }
        }
        $query->orderBy('date_of_publication DESC');

        return $query;
    }
}