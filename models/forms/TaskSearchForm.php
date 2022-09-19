<?php

namespace app\models\forms;

use app\models\Task;
use yii\base\Model;

class TaskSearchForm extends Model {

    public $categories;
    public $bonus;
    public bool $withoutResponses = true;
    public bool $isDistant = false;
    public $period;
    const SEARCH_INTERVAL = ['1 час', '12 часов', '24 часа', 'все'];
    public function rules(): array
    {
        return [
            [['isDistant', 'withoutResponses'], 'boolean'],
            ['period', 'in', 'range' => self::SEARCH_INTERVAL],
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

    function getInterval() {
        switch($this->period){
            case self::SEARCH_INTERVAL[0]: $interval = '<= NOW() - INTERVAL 1 HOUR'; break;
            case self::SEARCH_INTERVAL[1]: $interval = '<= NOW() - INTERVAL 12 HOUR'; break;
            case self::SEARCH_INTERVAL[2]: $interval = '<= NOW() - INTERVAL 24 HOUR'; break;
            case self::SEARCH_INTERVAL[3]: $interval = '>= NOW()';
        }
        return $interval;
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
            $query->andWhere('date_of_publication ' . $this->getInterval());
        }
        $query->orderBy('date_of_publication DESC');

        return $query;
    }
}