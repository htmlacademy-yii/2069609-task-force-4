<?php

namespace app\models\forms;

use app\models\Response;
use app\models\Task;
use app\models\User;
use Exception;
use Yii;
use yii\base\Model;

class RefuseForm extends Model
{
    public $proof;
    public function rules()
    {
        return [
            [['proof'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'proof' => '',
        ];
    }

    /**
     * @throws Exception
     */
    public function refuseTask($id_task){
        $transaction = Yii::$app->db->beginTransaction();
        try {
        $task = Task::findOne($id_task);
        $task->status = Task::STATUS_FAILED;
        $task->save();
        $response = Response::findOne(['task_id' => $id_task, 'status' => 1]);
        $response->score = 0;
        $user = User::findOne(Yii::$app->user->id);
        $user->availability = 1;
        $user->save();
        $response->save();
        $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception('Loading error');
        }
    }
}