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
    const WITHOUT_RESPONSES = 'Без откликов';
    const DISTANT_WORK = 'Удаленная работа';

    const SEARCH_INTERVAL = [self::HOUR_1, self::HOURS_12, self::HOURS_24, self::ALL_TASKS];

    public function rules(): array
    {
        return [
            [['isDistant', 'withoutResponses'], 'boolean'],
            ['period', 'in', 'range' => self::SEARCH_INTERVAL],
            ['categories', 'exist', 'targetClass' => Category::class, 'targetAttribute' => ['categories' => 'id']]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'categories' => 'Категории',
            'bonus' => 'Дополнительно',
            'period' => 'Период'
        ];
    }

    private function getQueryPeriod($query)
    {
        switch($this->period){
            case self::HOUR_1: $interval = 'date_of_publication <= NOW() - INTERVAL 1 HOUR'; break;
            case self::HOURS_12: $interval = 'date_of_publication <= NOW() - INTERVAL 12 HOUR'; break;
            case self::HOURS_24: $interval = 'date_of_publication <= NOW() - INTERVAL 24 HOUR'; break;
            case self::ALL_TASKS: $interval = 'date_of_publication >= NOW()'; break;
        }
        return $query->andWhere($interval);
    }

    public function search(): \yii\db\ActiveQuery
    {
        $query = Task::find();
        $query->where(['status' => Task::STATUS_NEW]);

        if ($this->isDistant) {
            $query->andWhere('latitude is null');
        }
        if ($this->categories){
            $query->andWhere(['in', 'category_id', $this->categories]);
        }
        if ($this->withoutResponses) {
            $query->leftJoin('response', 'response.task_id = null');
        }
        if (in_array($this->period, self::SEARCH_INTERVAL)){
            self::getQueryPeriod($query);
        }
        $query->orderBy('date_of_publication DESC');

        return $query;
    }
}