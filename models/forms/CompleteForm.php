<?php

namespace app\models\forms;

use app\models\Response;
use app\models\Task;
use Yii;
use yii\base\Model;
use Exception;

class CompleteForm extends Model
{
    public $feedback;
    public $score;

    public function rules()
    {
        return [
            [['feedback', 'score'], 'required'],
            ['feedback', 'string'],
            ['score', 'integer', 'min' => 1, 'max' => 5],
        ];
    }
    public function attributeLabels()
    {
        return [
            'feedback' => 'Ваш комментарий',
            'score' => 'Оценка работы'
        ];
    }

    /**
     * @throws Exception
     */
    public function completeTask($id_task){
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $task = Task::findOne($id_task);
            $response = Response::findOne([
                'task_id' => $id_task,
                'status' => 1,
            ]);
            $response->score = $this->score;
            $response->feedback = $this->feedback;
            $task->status = Task::STATUS_DONE;
            $user = $response->user;
            $user->availability = 1;
            $task->save();
            $response->save();
            $user->rating = $user->getRating($user->id);
            $user->save();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception('Loading error');
        }
    }

}