<?php

namespace Delta\TaskForce;

use app\models\Response;
use app\models\Task;
use app\models\User;
use Exception;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Yii;
use yii\web\ForbiddenHttpException;

class RespondAction extends Action
{
    const ACTION = 'Откликнуться';
    const NAME = 'respond';

    public function getAction(): string
    {
        return self::ACTION;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public static function isAvailable(Task $task, int $userCurrentId): bool
    {
        $userCurrent = User::findOne($userCurrentId);
        $isCurrentUserExecutor = true;
        $doesTaskHaveNotActiveResponse = true;
        $isExecutorAvailable = true;
        $isResponseNotRepeated = true;

        if ($userCurrent->role != User::ROLE_EXECUTOR){
            $isCurrentUserExecutor = false;
        }
        if (Response::find()->where(['task_id' => $task->id, 'status' => Response::STATUS_ACTIVE_RESPONSE])->exists()) {
            $doesTaskHaveNotActiveResponse = false;
        }
        if ($userCurrent->availability !== User::IS_AVAILABILITY){
            $isExecutorAvailable = false;
        }
        if (Response::find()->where(['user_id' => $userCurrentId, 'task_id' => $task->id])->exists()) {
            $isResponseNotRepeated = false;
        }
        return $isCurrentUserExecutor && $doesTaskHaveNotActiveResponse && $isExecutorAvailable && $isResponseNotRepeated;
    }


    public function getClass(): string
    {
        return 'button button--blue action-btn';
    }

    public function getDataAction(): string
    {
        return 'act_response';
    }
    public function getUrlName(): string
    {
        return '#';
    }
}
