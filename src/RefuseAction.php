<?php

namespace Delta\TaskForce;

use app\models\Response;
use app\models\Task;
use yii\helpers\Url;

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

    //буду сравнивать с idExecutor
    public static function isAvailable(Task $task, int $userCurrentId): bool
    {
        $response = Response::findOne(['task_id' => $task->id, 'status' => 1]);
        if ($response === null){
            return false;
        } else {
            if ($userCurrentId === $response->user_id) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getClass(): string
    {
        return 'button button--orange action-btn';
    }

    public function getDataAction(){
        return 'refusal';
    }

    public function getUrlName()
    {
        return '#';
    }
}
