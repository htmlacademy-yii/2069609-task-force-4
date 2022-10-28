<?php

namespace Delta\TaskForce;

use app\models\Response;
use app\models\Task;
class RefuseAction extends Action
{
    const ACTION = 'Отказаться';
    const NAME = 'refuse';

    public function getAction(): string
    {
        return self::ACTION;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    //Отказаться от задания может только активный исполнитель данного задания
    public static function isAvailable(Task $task, int $userCurrentId): bool
    {
        $response = Response::findOne(['task_id' => $task->id, 'status' => Response::STATUS_ACTIVE_RESPONSE, 'user_id' => $userCurrentId]);
        if ($response === null){
            return false;
        } else {
            return true;
        }
    }

    public function getClass(): string
    {
        return 'button button--orange action-btn';
    }

    public function getDataAction(): string
    {
        return 'refusal';
    }

    public function getUrlName(): string
    {
        return '#';
    }
}
